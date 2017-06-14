<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Expense'), ['action' => 'edit', $expense->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Expense'), ['action' => 'delete', $expense->id], ['confirm' => __('Are you sure you want to delete # {0}?', $expense->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Expenses'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Expense'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Categories'), ['controller' => 'Categories', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Category'), ['controller' => 'Categories', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="expenses view large-9 medium-8 columns content">
    <h3><?= h($expense->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $expense->has('user') ? $this->Html->link($expense->user->id, ['controller' => 'Users', 'action' => 'view', $expense->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Category') ?></th>
            <td><?= $expense->has('category') ? $this->Html->link($expense->category->name, ['controller' => 'Categories', 'action' => 'view', $expense->category->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($expense->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Value') ?></th>
            <td><?= $this->Number->format($expense->value) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($expense->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($expense->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Date') ?></th>
            <td><?= h($expense->date) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($expense->description)); ?>
    </div>
    <div class="row">
        <h4><?= __('Remark') ?></h4>
        <?= $this->Text->autoParagraph(h($expense->remark)); ?>
    </div>
</div>
