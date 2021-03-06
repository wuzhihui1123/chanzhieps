<?php
/**
 * The latest article front view file of block module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.chanzhi.org
*/
?>
<?php 
/* Set $themRoot. */
$themeRoot = $this->config->webRoot . 'theme/';

/* Decode the content and get articles. */
$content = json_decode($block->content);
$method  = 'get' . ucfirst(str_replace('thread', '', strtolower($block->type)));
$threads = $this->loadModel('thread')->$method(empty($content->category) ? 0 : $content->category, $content->limit);
?>
<div id="block<?php echo $block->id;?>" class='panel panel-block <?php echo $blockClass;?>'>
  <div class='panel-heading'>
    <strong><?php echo $icon . $block->title;?></strong>
    <?php if(!empty($content->moreText) and !empty($content->moreUrl)):?>
    <div class='pull-right'><?php echo html::a($content->moreUrl, $content->moreText);?></div>
    <?php endif;?>
  </div>
  <div class='panel-body'>
    <ul class='ul-list'>
      <?php foreach($threads as $thread): ?>
      <li>
        <?php echo html::a(helper::createLink('thread', 'view', "id=$thread->id"), $thread->title);?>
        <span class='pull-right'><?php echo substr($thread->addedDate, 0, 10);?></span>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
