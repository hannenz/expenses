<?php
/**
  * @var \App\View\AppView $this
  */
use App\View\Helper\MyFormHelper;
?>
<div class="expenses form">
	<button onclick="window.location='/';" class="close-button">&times;</button>
	<?php 
		$this->Form->setTemplates([
			'dateWidget' => '{{day}}{{month}}{{year}}'
		]);
		echo $this->Form->create($expense, ['novalidate' => true]);
	?>
	<a class="close-button" href="/"> &times </a>
    <fieldset>
        <legend><?= __('Add Expense') ?></legend>
        <?php
            echo $this->Form->control('value', ['step' => '0.01', 'required' => false]);
            echo $this->Form->control('description', ['type' => 'text', 'list' => 'descriptions']);
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('category_id', ['options' => $categories]);
			echo $this->MyForm->control('date', ['type' => 'date']);
            echo $this->Form->control('remark');
        ?>
	<datalist id="descriptions">
		<?php foreach ($descriptions as $description): ?>
			<option value="<?=h($description);?>"><?=h($description);?></option>
		<?php endforeach ?>
	</datalist>
    </fieldset>
    <?= $this->Form->button('✓') ?>
    <?= $this->Form->end() ?>
	<?= $this->Html->script('expense_add.js', ['block' => 'bottomscripts']); ?>
</div>
