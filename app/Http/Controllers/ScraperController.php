<?php

namespace App\Http\Controllers;

use Goutte\Client;
use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScraperController extends Controller
{
    private function scaperConnect($url)
    {
        $client = new Client();
        try{
            return $client->request('GET', $url);
        }catch(\Exception $e)
        {
            echo $e->getMessage();
            die();
        }
    }

    public function scraper()
    {   
        $scraper = $this->scaperConnect("https://news.ycombinator.com/");  
       
        $data = [];   
        $scraper->filter('.athing')->each(function ($node) use (&$data) {  
        
            $id = $node->filter('tr')->attr('id');  

            $title = $node->filter('.titleline')->text();  
        
            $link = $node->filter('.titleline > a')->attr('href');
        
            $data[$id]['title'] = $title; 
            $data[$id]['link'] = $link;  

        });

        $scraper->filter('.subline')->each(function ($node) use (&$data) {  
        
            $id = trim($node->filter('.score')->attr('id'), 'score_');  
            
            $score = trim($node->filter('.score')->text(), ' points');  
        
            $date = $node->filter('.age')->attr('title');
        
            $data[$id]['score'] = $score;
            $data[$id]['date'] = $date;  

        });

        $this->storePostsToDatabase($data);

        return redirect()->route('scraper.view');
    }


    public function update()
    {   
        $scraper = $this->scaperConnect("https://news.ycombinator.com/");  

        $scraper->filter('.subline')->each(function ($node) use (&$data) {  
        
            $id = trim($node->filter('.score')->attr('id'), 'score_');  
            
            $existingPost = Data::where('item_id', $id)->first();

            if($existingPost){
                $score = trim($node->filter('.score')->text(), ' points');

                $existingPost->update([
                        'score' => $score,
                    ]);
            }
        });

        return redirect()->route('scraper.view');
    }


    public function delete($id)
    {
        $post = Data::findOrFail($id);
        
        $post->delete();

        return redirect()->route('scraper.view');
    }


    function storePostsToDatabase($data)
    {
        foreach ($data as $item_id => $post) {

            $existingPost = Data::where('item_id', $item_id)->first();
            if ($existingPost) {
                $existingPost->update([
                    'title' => $post['title'] ?? null,
                    'link' => $post['link'] ?? null,
                    'score' => $post['score'] ?? null,
                    'date' => $post['date'] ?? null,
                ]);
            }
            else{
                $newPost = new Data();

                $newPost->item_id = $item_id;
                $newPost->title = isset($post['title']) ? $post['title'] : null;
                $newPost->link = isset($post['link']) ? $post['link'] : null;
                $newPost->score = isset($post['score']) ? $post['score'] : null;
                $newPost->date = isset($post['date']) ? $post['date'] : null;

                $newPost->save();
            }
        }
    }


    public function showData()
    {
        $data = Data::all();

        return view('scraper.view', compact('data'));
    }
}
