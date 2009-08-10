<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php echo $this->list['name']['link']; ?>
<table>
  <tr>
<?php foreach($this->list as $key => $field): ?>
    <th scope="col"><?php echo $field['label']; ?></th>
<?php endforeach; ?>
  </tr>
  <tr>
<?php foreach($this->list as $key => $field): ?>
    <td><?php echo $field['value']; ?></td>
<?php endforeach; ?>
  </tr>
</table>

<table>
  <tr>
<?php foreach($this->items[0] as $key => $field): ?>
    <th scope="col"><?php echo $field['label']; ?></th>
<?php endforeach; ?>
  </tr>
<?php foreach($this->items as $item): ?>
  <tr>
<?php foreach($item as $key => $field): ?>
    <td><?php echo $field['value']; ?></td>
<?php endforeach; ?>
  </tr>
<?php endforeach; ?>
</table>

<?php echo $this->pagination; ?>

</div>
