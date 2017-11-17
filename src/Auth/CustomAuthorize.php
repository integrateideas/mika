<?php
namespace App\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;
use Cake\Utility\Inflector;

class CustomAuthorize extends BaseAuthorize{
	
	public function authorize($user, Request $request){
		//setting request parameters
        return true;
		$this->reqController = $request->params['controller'];
		$this->reqAction = $request->params['action'];
		$this->reqPass = $request->params['pass'];
		$this->reqPrefix = isset($request->params['prefix']) ? $request->params['prefix'] : false;
		$roles = TableRegistry::get('Roles');
        $user['role'] = $roles->find('RolesById', ['role' => $user['role_id']])
                              ->select(['name', 'label'])
                              ->first();

		$userRole = $user['role']['label'];

		//Check if user allowed to acces the resource based on his role
		if(!$this->_checkRoleAccess($userRole)){
			
			return false;
		} 
		//if acessing a record then check ownership
        if(isset($this->reqPass[0]) && is_numeric($this->reqPass[0]) && in_array($userRole, ['User', 'Expert'])){

            if($this->_checkExemptedLocations($userRole)){
                return true;               
            }
            if(!$this->_checkOwnerShip($user['id'],$userRole, $this->reqPass[0])){
                return false;
            }    
	  	}	

		return true;
	}

	//method to check wether current controller & action matches some list of un authorized controllers 
	private function _checkUnAuthorized($unAuthorizedLocations){

		if(isset($unAuthorizedLocations[$this->reqController])){

			if($unAuthorizedLocations[$this->reqController][0] == 'all' || in_array($this->reqAction, $unAuthorizedLocations[$this->reqController])){
				
				return false;
			}
		}	

		return true;

	}

	 private function _checkRoleAccess($userRole){

        switch ($userRole) {
            case 'Admin':
              
                return true;	

            case 'Expert':
               
                if($this->reqPrefix != 'api'){
            	
                    return false;
                }
                
                return true;

            case 'User':
                if($this->reqPrefix != 'api/user'){
                    return false;
                }

                return true;   

            default:
                return false;
                break;
        }   
    }

    //method to get unauthorized locations for current user based on the resource ownership of the vendor
    private function _checkOwnerShip($targetId, $userRole, $entityId){
       

        $target = Inflector::pluralize($userRole);
      
        // $associationRoute = $this->_getFromKnownRoutes($this->reqController, $target);
        
        // if(!$associationRoute){
       		$associationRoute = $this->_getAssociationRoute($this->reqController, $target, [], []);
        // }


        if(!$associationRoute){
            return true;
        }
        // $associationRoute = $this->_getAssociationRoute('Features', $target, [], []);
        if ($associationRoute == $target) {
           
            if($targetId == $entityId){
                return true;
            }
            return false;

        }elseif ($associationRoute[0] == $target && count($associationRoute) == 1) {
           
            $direct = true;
            $tableObject = TableRegistry::get($this->reqController);
            $associations = $tableObject->associations();
            $foreignKey = $associations->get($target)->foreignKey();
            $entity = $tableObject->findById($entityId)->where([$foreignKey => $targetId])->first();


        } else{
           
            $direct = false;
            $pathToModel = $this->_decorateRoute($associationRoute);
            $tableObject = TableRegistry::get($this->reqController);
            $entity = $tableObject->findById($entityId)->matching($pathToModel, function($q) use ($targetId, $target){
                return $q->where([$target.'.id' => $targetId]);
            })->first();
        }

        //Check Vendor OwnerShip
        if(isset($entity) && $entity){
            return true;
        }
        // return false;
        unset($entity);
        //Check Super Admin OwnerShip
           
        return false;
    }

    //Calculates an association route between source and target model
    private function _getAssociationRoute($source, $target, $route, $exclude){
    	
        if($source == $target) {
            return $target;
        }
        
        $tableObject = TableRegistry::get($source);
        
        // pr($tableObject);die;
        $entityClass = $tableObject->entityClass();
        // pr($entityClass);die;
        $entityClass = (new \ReflectionClass($entityClass))->getShortName();
        //Check if Model Exists or not ($entityClass will equal to 'Entity if no model exists')
        if($entityClass == 'Entity'){
            return false;
        }

        $associations = $tableObject->associations();
        unset($tableObject);
        // pr($associations);die;
        $array = $associations->keys();
        // pr($array);die;
        $source = strtolower($source);
        $target = strtolower($target);
   
        if(!$array){
            return false;
        }

        if(in_array($target, $array)){
            $route[] = $associations->get($target)->name();
            return $route;
        }else{
           
            $exclude[] = $source;
            foreach ($array as $key => $value) {

                if(!in_array($value, $exclude)){
                    $value = $associations->get($value)->name();
                    $check = $this->_getAssociationRoute($value, $target, $route, $exclude);
                    if($check != false){
                        $check[] = $value;
                        return $check;
                    }
                }
            }
            return false;

        }
    }

    private function _checkExemptedLocations($userRole){

        $allowedLocations = [

            'User' => [
                'ExpertProfile' => ['view','todaysAvailabilities'],
                'UserFavouriteExperts' => ['delete']
            ],
            'Expert' => [
                'ExpertSpecializations' => ['edit','delete']
            ]
        ];
      
        return !$this->_checkUnAuthorized($allowedLocations[$userRole]);
    }

    //Use known routes instead of find routes recursively
    // private function _getFromKnownRoutes($source, $target){

    //     $knownRoutes = [

    //         'Expert' => [

    //             'Users' => ['Resellers','ResellerVendors','Vendors','VendorUsers']
    //         ],
    //         'Vendors' => [

    //             'Users' => ['Vendors', 'VendorUsers'],
    //         ]
    //     ];

    //     if(isset($knownRoutes[$target][$source])){
    //         return $knownRoutes[$target][$source];
    //     }

    //     return false;
    // }

}

?>
