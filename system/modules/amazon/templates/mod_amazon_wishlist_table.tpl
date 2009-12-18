<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->error): ?>
  <div class="error"><?php echo $this->error; ?></div>
<?php else: ?>
<?php if ($this->headline): ?>
  <<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
  <?php echo $this->list['name']['link']; ?>
  <table>
    <tr>
<?php foreach($this->list as $key => $field): ?>
<?php if ($key != 'xml'): ?>
      <th scope="col"><?php echo $field['label']; ?></th>
<?php endif; ?>
<?php endforeach; ?>
    </tr>
    <tr>
  <?php foreach($this->list as $key => $field): ?>
<?php if ($key != 'xml'): ?>
      <td><?php echo $field['value']; ?></td>
<?php endif; ?>
  <?php endforeach; ?>
    </tr>
  </table>

  <table>
    <tr>
  <?php foreach($this->items[0] as $key => $field): ?>
<?php if ($key != 'xml' && $key != 'class'): ?>
      <th scope="col"><?php echo $field['label']; ?></th>
<?php endif; ?>
  <?php endforeach; ?>
    </tr>
  <?php foreach($this->items as $item): ?>
    <tr class="<?php echo $item['class']; ?>">
  <?php foreach($item as $key => $field): ?>
<?php if ($key != 'xml' && $key != 'class'): ?>
      <td><?php echo ($field['link']) ? $field['link'] : $field['value']; ?></td>
<?php endif; ?>
  <?php endforeach; ?>
    </tr>
  <?php endforeach; ?>
  </table>
  <?php echo $this->pagination; ?>
<?php endif; ?>
</div>
