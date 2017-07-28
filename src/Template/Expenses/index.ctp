<?php
/**
  * @var \App\View\AppView $this
  */
setlocale(LC_ALL, 'de_DE.utf8');
?>


<div class="expenses index">
    <h3><?= strftime('%B %Y', strtotime(sprintf('%04u-%02u-01', $year, $month))); ?></h3>

	<div style="display:flex">
		<?php foreach ($byCategory as $cat):?>
			<div style="background-color:gainsboro;margin-left:1px;margin-right:1px;width:<?php printf("%.2f%%", $cat->sum / $sum * 100); ?>">
			<dt title="<?=$cat->category->name;  ?>"><?= $cat->category->name; ?></dt>
			<dd title="<?=$this->Number->currency($cat->sum, 'EUR');?>"><?= $this->Number->currency($cat->sum, 'EUR'); ?></dt>
			</div>
		<?php endforeach ?>
	</div>
	<dl>
		<dt>Summe</dt>
		<dd><strong style="font-size:2em"><?= $this->Number->currency($sum, 'EUR'); ?></strong></dd>
	</dl>

	<hr>
	<h3>Bar chart</h3>

	<svg class="bar-chart" width="500" height="500" viewBox="0 0 500 500" role="img" style="display:block;width:500px;margin:0 auto;">

		<title>Bar Chart of the current month's expenses</title>
		<?php $i = 0; foreach ($byCategory as $cat):?>
			<?php if ($cat->category->parent_id != 1) continue; ?>
			<?php
				$w = $cat->sum / $sum * 500; 
				$y = $i++ * 25;
			?>
			<g class="bar">
				<rect x="0" y="<?=$y;?>" width="<?php printf("%.2F", $w); ?>" height="20" fill="salmon">
					<animate attributeName="width" from="0" to="<?php printf("%.2F", $w); ?>" dur="1s"  repeatCount="1" />
				</rect>
				<text id="bar-text-<?=$i; ?>" style="fill:#838284;font-size:12px;opacity:0" x="<?php printf("%.2F", $w + 5); ?>"	y="<?=$y + 14;?>"><?=$cat->category->name; ?> (<?=$this->Number->currency($cat->sum, 'EUR');?>)</text>
				<animate xlink:href="#bar-text-<?=$i;?>" transformType="CSS" attributeName="opacity" from="0" to="1" repeatCount="1" dur="500ms" begin="0.9s" fill="freeze" />
			</g>
		<?php endforeach; ?>
	</svg>
		
	
	

	<?php echo $this->Html->link(__('Add Expense'), ['action' => 'add', 'class' => 'button add-expense-button']); ?>

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
