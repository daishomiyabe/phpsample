<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ArticlesController extends AppController
{
	public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
    }
    
    public function view($slug = null)
	{
	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    $this->set(compact('article'));
	}
	
	public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            $article->user_id = $this->Auth->user('id');

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Your article has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your article.'));
        }
        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list');

        // ビューコンテキストに tags をセット
        $this->set('tags', $tags);

        $this->set('article', $article);
    }
    
    public function edit($slug)
	{
	    $article = $this->Articles->findBySlug($slug)->contain('Tags')->firstOrFail();
	    if ($this->request->is(['post', 'put'])) {
	        $this->Articles->patchEntity($article, $this->request->getData(),[
	        	'accessibleFields' => ['user_id' => false]
	        ]);
	        
	        if ($this->Articles->save($article)) {
	            $this->Flash->success(__('Your article has been updated.'));
	            return $this->redirect(['action' => 'index']);
	        }
	        $this->Flash->error(__('Unable to update your article.'));
	    }
	    // タグのリストを取得
        $tags = $this->Articles->Tags->find('list');

        // ビューコンテキストに tags をセット
        $this->set('tags', $tags);

	    $this->set('article', $article);
	}
	
	public function tags()
	{
	    // 'pass' キーは CakePHP によって提供され、リクエストに渡された
	    // 全ての URL パスセグメントを含みます。
	    $tags = $this->request->getParam('pass');

	    // ArticlesTable を使用してタグ付きの記事を検索します。
	    $articles = $this->Articles->find('tagged', [
	        'tags' => $tags
	    ]);

	    // 変数をビューテンプレートのコンテキストに渡します。
	    $this->set([
	        'articles' => $articles,
	        'tags' => $tags
	    ]);
	}
	
	public function isAuthorized($user){
		$action = $this->request->getParam('action');
		if(in_array($action,['add','tags'])){
			return true;
		}
		
		$slug = $this->request->getParam('pass.0');
		if(!$slug){
			return false;
		}
		$article = $this->Articles->findBySlug($slug)->first();
		
		return $article->user_id === $user['id'];
	}
	
	public function delete($slug)
	{
	    $this->request->allowMethod(['post', 'delete']);

	    $article = $this->Articles->findBySlug($slug)->firstOrFail();
	    if ($this->Articles->delete($article)) {
	        $this->Flash->success(__('The {0} article has been deleted.', $article->title));
	        return $this->redirect(['action' => 'index']);
	    }
	}
}