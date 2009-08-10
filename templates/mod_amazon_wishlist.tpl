<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>
<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<h3><?php echo $this->list['name']['value']; ?> <span class="name"><?php echo $this->list['customername']['value']; ?></span></h3>
<p class="geaendert"><?php echo $this->list['lastmodified']['label']; ?> <span class="datum"><?php echo $this->list['lastmodified']['value']; ?></span></p>
<?php echo $this->list['name']['link']; ?>

<?php foreach($this->items as $item): ?>
<?php //var_dump($item); ?>
<div class="item wishlist">
  <dl class="<?php echo $item['class']; ?>">
    <dt class="title_item">
      <a href="<?php echo $item['buynow']['url']; ?>" title="<?php echo $item['buynow']['label']; ?>">
	<span class="title"><?php echo $item['title']['label']; ?></span> <?php echo $item['title']['value']; ?>
    </a>
    </dt>
    <dd class="pic">
      <?php echo $item['image']['lightbox']; ?>
    </dd>
    <dd class="author"><span class="title">Autor</span> </dd>
    <dd class="hinzugefuegt_am"><span class="title"><?php echo $item['dateadded']['label']; ?></span> <?php echo $item['dateadded']['value']; ?></dd>
    <dd class="gewuenscht"><span class="title"><?php echo $item['quantitydesired']['label']; ?></span> <?php echo $item['quantitydesired']['value']; ?></dd>
    <dd class="erhalten"><span class="title"><?php echo $item['quantityreceived']['label']; ?></span> <?php echo $item['quantityreceived']['value']; ?></dd>
    <dd class="prioritaet"><span class="title"><?php echo $item['priority']['label']; ?></span> <?php echo $item['priority']['value']; ?></dd>
    <dd class="status"><span class="title"><?php echo $item['availability']['label']; ?></span> <?php echo $item['availability']['value']; ?></dd>
    <dd class="asin"><span class="title"><?php echo $item['asin']['label']; ?></span> <?php echo $item['asin']['value']; ?></dd>
    <dd class="preis"><span class="title"><?php echo $item['price']['label']; ?></span> <?php echo $item['price']['value']; ?></dd>
  </dl>
</div>
<?php //var_dump($item['xml']); ?>
<?php endforeach; ?>
<p class="back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>

</div>