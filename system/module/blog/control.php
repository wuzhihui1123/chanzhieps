<?php
/**
 * The control file of blog module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     blog
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class blog extends control
{
    /** 
     * Browse blog in front.
     * 
     * @param int    $categoryID   the category id
     * @param int    $pageID       current page id
     * @access public
     * @return void
     */
    public function index($categoryID = 0, $pageID = 1)
    {   
        $category = $this->loadModel('tree')->getByID($categoryID, 'blog');

        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $this->config->blog->recPerPage, $pageID);

        $categoryID = is_numeric($categoryID) ? $categoryID : $category->id;
        $articles   = $this->loadModel('article')->getList('blog', $this->tree->getFamily($categoryID, 'blog'), $orderBy = 'addedDate_desc', $pager);

        $this->view->title      = $this->lang->blog->common;
        $this->view->categoryID = $categoryID;
        $this->view->articles   = $articles;
        $this->view->pager      = $pager;
 
        if($category)
        {
            if($category->link) helper::header301($category->link);

            $this->view->category = $category;
            $this->view->title    = $category->name;
            $this->view->keywords = trim($category->keywords . ' ' . $this->config->site->keywords);
            $this->view->desc     = strip_tags($category->desc);
            $this->session->set('articleCategory', $category->id);
        }

        $this->display();
    }
    
    /**
     * View an article.
     * 
     * @param int $articleID 
     * @param int $currentCategory 
     * @access public
     * @return void
     */
    public function view($articleID, $currentCategory = 0)
    {
        $article = $this->loadModel('article')->getByID($articleID);
        if(!$article) die($this->fetch('error', 'index'));

        if($article->link) helper::header301($article->link);

        /* fetch category for display. */
        $category = array_slice($article->categories, 0, 1);
        $category = $category[0]->id;

        $currentCategory = $this->session->articleCategory;
        if($currentCategory > 0 && isset($article->categories[$currentCategory])) $category = $currentCategory;  
        $category = $this->loadModel('tree')->getByID($category);

        $title    = $article->title . ' - ' . $category->name;
        $keywords = $article->keywords . ' ' . $category->keywords . ' ' . $this->config->site->keywords;
        $desc     = strip_tags($article->summary);
        
        $this->view->title       = $title;
        $this->view->keywords    = $keywords;
        $this->view->desc        = $desc;
        $this->view->article     = $article;
        $this->view->prevAndNext = $this->loadModel('article')->getPrevAndNext($article->id, $category->id);
        $this->view->category    = $category;
        $this->view->contact     = $this->loadModel('company')->getContact();

        $this->dao->update(TABLE_ARTICLE)->set('views = views + 1')->where('id')->eq($articleID)->exec(false);
        $this->display();
    }
}
