<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Expense'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="expenses index">
    <h3><?= __('Expenses') ?></h3>

	<div style="display:flex">
		<?php foreach ($byCategory as $cat):?>
			<div style="background-color:gainsboro;margin-left:1px;margin-right:1px;width:<?php printf("%.2f%%", $cat->sum / $sum * 100); ?>">
			<dt><?= $cat->category->name; ?></dt>
			<dd><?= $this->Number->currency($cat->sum, 'EUR'); ?></dt>
			</div>
		<?php endforeach ?>
	</div>
	<dl>
		<dt>Summe</dt>
		<dd><strong><?= $sum  ?></strong></dd>
	</dl>


    <table cellpadding="0" cellspacing="0">
        <tbody>
            <?php foreach ($expenses as $expense): ?>
            <tr>
                <td data-label="Datum"><?php echo $expense->date; // h(strftime('%d.%m.%Y', strtotime($expense->date))) ?></td>
                <td data-label="Kategorie"><?= $expense->category->name ?></td>
		<td data-label="Beschreibung"><?= $this->Html->link($expense->description, ['action' => 'view', $expense->id]) ?></td>
		<td data-label="Verursacher"><?= $expense->user->firstname; ?></td>
                <td data-label="Betrag"><?= $this->Number->currency($expense->value, 'EUR') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<!--
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
-->
</div>
