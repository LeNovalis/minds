<?php
/**
 * Minds Wallet API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\payments\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\api\factory;

class wallet implements interfaces\api{

    /**
     * Returns the wallet info
     * @param array $pages
     * 
     * API:: /v1/wallet/:slug
     */      
    public function get($pages){
        
        $response = array();
        
        switch($pages[0]){
            
            case "count":
                $db=new Core\Data\Call('entities');
                $slice = $db->getRow(Core\session::getLoggedinUser()->guid, array("offset"=>"points_count", "limit"=>1));
                $count = isset($slice['points_count']) ? (int) $slice['points_count'] : 0;
                
                $satoshi_rate = 1;//@todo make this configurable for admins
                $satoshi = $count * $satoshi_rate;
                $btc = ($satoshi / 1000000000);
            
                $response['count'] = $count;
                $response['satoshi'] = $satoshi;
                $response['btc'] = sprintf('%.9f', $btc);
                break;
                
            case "transactions":
                $entities = Core\entities::get(array('subtype'=>'points_transaction', 'owner_guid'=> Core\session::getLoggedinUser()->guid, 'limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                $response['transactions'] = factory::exportable($entities);
                $response['load-next'] = end($entities)->guid;
                break;
                
        }
    
        return factory::response($response);
        
    }
    
    public function post($pages){
        

        return factory::response($response);
    }
    
    public function put($pages){
        
        return factory::response(array());
        
    }
    
    public function delete($pages){
        
        return factory::response(array());
        
    }
    
}
        
