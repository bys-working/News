<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use \Application\Form\NewsForm;
use \Application\Model\News;
use Zend\Feed\Writer\Feed;
use Zend\View\Model\FeedModel;
use Zend\View\Model\JsonModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mail;

class NewsController extends AbstractActionController
{
    
    public function listNewsAction()
    {
        $sm = $this->getServiceLocator();
 
        $config = $sm->get('config');
        $imagesUrl = $config['imagesUrl'];
        
        $newsTable = $sm->get('NewsTable');
        $news = $newsTable->getNews();

        return array(
            'news' => $news,
            'imagesUrl' => $imagesUrl
        );
    }
    
    public function listNotApprovedNewsAction()
    {
        $sm = $this->getServiceLocator();
        $config = $sm->get('config');
        $imagesUrl = $config['imagesUrl'];
        
        $newsTable = $sm->get('NewsTable');
        $news = $newsTable->getNews(0);
        
        return array(
            'news' => $news,
            'imagesUrl' => $imagesUrl
        );
    }
    
    public function addNewsAction()
    {
        $sm = $this->getServiceLocator();
        $newsTable = $sm->get('NewsTable');
        $request = $this->getRequest();
        $config = $sm->get('config');
        $imagesPath = $config['imagesPath'];
        $user_id = $sm->get('zfcuser_auth_service')->getIdentity()->getId();

        $form = new NewsForm;
        if ($request->getPost('save')) {

            $news = new News;
            $form->setInputFilter($news->getInputFilter());
            $post = array_merge_recursive(
                $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            
            $form->setData($post);

            if ($form->isValid()) {

                if (!is_dir($imagesPath)) {
                    if (!mkdir($imagesPath, 0775, true)) {
                        throw new \Exception("Can't create directory!");
                    }
                }

                $request = new Request();

                $files = $request->getFiles();

                $filename = date('Y-m-d-H-i-s') . '-' . $files['image']['name'];
                $filter = new \Zend\Filter\File\RenameUpload($imagesPath . $filename);
                $filter->filter($files['image']);
                
                $news->exchangeArray($form->getData());
                $news->image = $filename;
                $news->user_id = $user_id;
                if ($newsTable->save($news)) {
                    

                    $mail = new Mail\Message();
                    $mail->setBody('This is the text of the email.');
                    $mail->setFrom('Freeaqingme@example.org', 'Sender\'s name');
                    $mail->addTo('Matthew@example.com', 'Name of recipient');
                    $mail->setSubject('TestSubject');

                    $transport = new Mail\Transport\Sendmail();
                    $transport->send($mail);

                    $this->flashMessenger()->setNamespace('success')->addMessage('Success add news.');
                    return $this->redirect()->toRoute('listNews');
                } else {
                    $this->flashMessenger()->setNamespace('error')->addMessage('Error saving news!');
                }
            }
        }

        return array(
            'form' => $form,
        );
    }
    
    public function approveNewsAction()
    {
        $sm = $this->getServiceLocator();
        $newsTable = $sm->get('NewsTable');
        $userTable = $sm->get('UserTable');
        $id = $this->params('id', 0);
        
        $news = $newsTable->getNewsById($id);
        $user = $userTable->getUserById($news['user_id']);

        $news['approved'] = 1;
        
        if ($newsTable->save($news)) {
            $mail = new Mail\Message();
            $mail->setBody('This is the text of the email.');
            $mail->setFrom('Freeaqingme@example.org', 'Sender\'s name');
            $mail->addTo($user->email, 'Name of recipient');
            $mail->setSubject('TestSubject');
                    
            $this->flashMessenger()->setNamespace('success')->addMessage('Success');
            return $this->redirect()->toRoute('listNews');
        } else {
            $this->flashMessenger()->setNamespace('error')->addMessage('Error');
        }

    }
    
    public function newsJSONAction()
    {
        $sm = $this->getServiceLocator();
        
        $newsTable = $sm->get('NewsTable');
        $news = $newsTable->getNews();

        return new JsonModel($news);
    }
    
    public function newsXmlAction()
    {
        $sm = $this->getServiceLocator();

        $newsTable = $sm->get('NewsTable');
        $news = $newsTable->getNews();

        $feed = new Feed();
        $feed->setTitle('Feed Example');
        $feed->setFeedLink('http://ourdomain.com/rss', 'atom');
        $feed->addAuthor(array(
            'name' => 'admin',
            'email' => 'contact@ourdomain.com',
            'uri' => 'http://www.ourdomain.com',
        ));
        $feed->setDescription('Description of this feed');
        $feed->setLink('http://ourdomain.com');
        $feed->setDateModified(time());


        foreach ($news as $row) {
            //create entry...
            $entry = $feed->createEntry();
            $entry->setTitle($row['title']);
            $entry->setDescription($row['description']);

            $feed->addEntry($entry);
        }

        $feed->export('rss');

        $feedmodel = new FeedModel();
        $feedmodel->setFeed($feed);

        return $feedmodel;
    }
    
    
}

