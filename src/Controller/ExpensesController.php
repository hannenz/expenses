<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Expenses Controller
 *
 * @property \App\Model\Table\ExpensesTable $Expenses
 *
 * @method \App\Model\Entity\Expense[] paginate($object = null, array $settings = [])
 */
class ExpensesController extends AppController
{


    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($month = null, $year = null) {

		$this->year = ($year !== null) ? $year : (int)date('Y');
		$this->month = ($month !== null) ? $month : (int)date('m');

        $this->paginate = [
			'contain' => ['Users', 'Categories'],
			'order' => ['date' => 'desc'],
			'limit' => 32767,
			'maxLimit' => 32767,
			'where' => function($exp) {
				return $exp->between('date', sprintf('%04u-%02u-01', $this->year, $this->month), sprintf('%04u-%02u-31', $this->year, $this->month));
			}
        ];
        $expenses = $this->paginate($this->Expenses);

		$query = $this->Expenses
			->find()
			->where(function($exp) {
				return $exp->between('date', sprintf('%04u-%02u-01', $this->year, $this->month), sprintf('%04u-%02u-31', $this->year, $this->month)); 
			})
			->order(['date' => 'desc'])
			->contain(['Users', 'Categories'])
		;
		$expenses = $query->all();

        $this->set(compact('expenses'));
        $this->set('_serialize', ['expenses']);

		$query = $this->Expenses->find();
		$query->select(['sum' => $query->func()->sum('value')]);
		$query->where(function($exp, $q) {
			$year_month = $q->func()->date_format([
				'date' => 'identifier',
				"'%Y-%m'" => 'literal'
			]);
			return $exp->eq($year_month, sprintf('%04u-%02u', $this->year, $this->month));

		});
		$this->set('sum', $query->toArray()[0]->sum);


		$query = $this->Expenses->find();
		$query->select(['Expenses.category_id','Categories.name','sum' => $query->func()->sum('value')]);

		$query->contain([
			'Categories' => function($q) {
				return $q->select([ 'Categories.name', 'Categories.parent_id' ]);
			}
		]);

		$query->where(function($exp, $q) {
			$year_month = $q->func()->date_format([
				'date' => 'identifier',
				"'%Y-%m'" => 'literal'
			]);
			return $exp->eq($year_month, sprintf('%04u-%02u', $this->year, $this->month));
			// $month = $q->func()->month(['date' => 'identifier']);
			// return $exp->eq($month, (int)date('m'));
		});
		$query->group(['category_id']);

		//$expenses = $query->toArray();
		$this->set('byCategory', $query->all());
		$this->set('year', $this->year);
		$this->set('month', $this->month);

    }

    /**
     * View method
     *
     * @param string|null $id Expense id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $expense = $this->Expenses->get($id, [
            'contain' => ['Users', 'Categories']
        ]);

        $this->set('expense', $expense);
        $this->set('_serialize', ['expense']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add() {

        $expense = $this->Expenses->newEntity();
        if ($this->request->is('post')) {
            $expense = $this->Expenses->patchEntity($expense, $this->request->getData());

			// Should move somewhere else
			$checkExpense = $this->Expenses->find('all', [
				'conditions' => [
					'date' => $expense->date,
					'value' => $expense->value
			]]);

			$isDuplicate = ($checkExpense->count() >= 1);


			if ($isDuplicate) {
				$this->Flash->set(__('This expense seems to be a duplicate, please check!'));
				$this->set('duplicate_detected', true);
			}

			if (!$isDuplicate || (isset($expense->override_duplicate) && $expense->override_duplicate == 1)) {
			
				if ($this->Expenses->save($expense)) {
					$this->Flash->success(__('The expense has been saved.'));

					return $this->redirect(['action' => 'index']);
				}
				$this->Flash->error(__('The expense could not be saved. Please, try again.'));
			}
        }
        $users = $this->Expenses->Users->find('list', ['limit' => 200]);
        $categories = $this->Expenses->Categories->find('treelist', ['limit' => 200]);
		$descriptions = $this->Expenses->find('list', [
			'keyField' => 'id',
			'valueField' => 'description'
		]);
        $this->set(compact('expense', 'users', 'categories', 'descriptions'));
        $this->set('_serialize', ['expense']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Expense id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $expense = $this->Expenses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $expense = $this->Expenses->patchEntity($expense, $this->request->getData());
            if ($this->Expenses->save($expense)) {
                $this->Flash->success(__('The expense has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The expense could not be saved. Please, try again.'));
        }
        $users = $this->Expenses->Users->find('list', ['limit' => 200]);
        $categories = $this->Expenses->Categories->find('treelist');
        $this->set(compact('expense', 'users', 'categories'));
        $this->set('_serialize', ['expense']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Expense id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $expense = $this->Expenses->get($id);
        if ($this->Expenses->delete($expense)) {
            $this->Flash->success(__('The expense has been deleted.'));
        } else {
            $this->Flash->error(__('The expense could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
