<?php
/**
 * Mailchimp Lists and Vars
 */
 
 global $nm_mailchimp;
 
 //pa_nm_mailchimp($mc_lists);
 ?>
 
 <link rel="stylesheet" type="text/css" href="<?php echo $nm_mailchimp->plugin_meta['url']?>/css/grid/simplegrid.css" />
 <style type="text/css">
     .content {
				background: #fff;
				padding: 10px;
			}
			
	.mc-list-details{
	    list-style: none;
	}
	
	#nm_mailchimp-lists dt, dd { float: left }
    #nm_mailchimp-lists dt { clear:both }
    
    .ng-invalid.ng-dirty{border-color:red}
    .ng-valid.ng-dirty{border-color:green}
    
    .active-mc-list{background-color: #223B7B;}
    .active-mc-list a{color: #fff;}
 </style>
 
 
<div class="wrap" ng-app="mcListManager">

<div id="nm_mailchimp-lists">
 <h2>
	<?php _e("Mailchimp Lists and Variables", 'nm-mailchimp');?>
</h2>

<div class="mc-lists" ng-controller="listController as listCtrl">
    
    <div class="grid">
        <div class="col-8-12">
            <div class="content">
                <h3><?php _e("Total lists found: ", 'nm-mailchimp');?>{{listCtrl.total_lists}}</h3>
            </div>
        </div>
        <div class="col-4-12">
            <div class="content">
                <button ng-click="showFormControls()" ng-hide="createForm" class="button button-primary"><?php _e('Create Subscription Form', 'nm-mailchimp');?></button>
                
                <form name="subscriptionForm" ng-show="createForm && !theShortcode" ng-submit="subscriptionForm.$valid && generateShortcode()" novalidate>
                    <input type="hidden" ng-model="selectedInterest" />
                    <input type="hidden" ng-model="selectedVars" />
                <p>
                    <?php _e('Select Merge Vars and Interest Groups of selected List given below.', 'nm-mailchimp');?>
                
                </p>
                <p>
                    <label for="shortcodeForm"><?php _e('Detail', 'nm-mailchimp');?><br>
                    <textarea cols="45" rows="2" id="shortcodeForm" ng-model="form_name" placeholder="{{listCtrl.selectedList.name}}" required></textarea>
                    </label>
                    <br>
                    <input type="submit" class="button button-primary" value="Generate" />
                    <button ng-click="createForm = false" class="button"><?php _e('Cancel', 'nm-mailchimp');?></button>
                </p>
                </form>
                
                <div ng-show="theShortcode" style="text-align:center;">
                    <pre>{{theShortcode}}</pre>
                    <p><?php _e('Copy above shortcode and paste into your page. This also can be access in widgets', 'nm-mailchimp');?></p>
                </div>
            </div>
        </div>
    </div>
    
    
   
        <div class="grid" style="margin-top:15px;">
            <!-- showing all lists and controlls -->
            <div class="col-4-12">
               <div class="content">
                   <h3><span class="dashicons dashicons-list-view"></span><?php _e('Mialchimp Account Lists', 'nm-mailchimp'); ?> 
                   <a href title="Show/hide detail" ng-click="listCtrl.toggleDetail()">[{{listCtrl.detailIcon}}]</a></h3>
                   
                   <!-- custom directives -->
                   <mc-list></mc-list>
                   
               </div>
            </div>
            
            
            <!--  list related functions: Vars -->
            <div class="col-4-12">
               <div class="content">
                   
                   <p ng-hide="listCtrl.selectedList"><?php _e('Click any List from left side to see it\'s Variables', 'nm-mailchimp'); ?></p>
                  
                    <div ng-show="listCtrl.selectedList">
                          
                          <!-- custom directives for Vars -->
                          <list-vars></list-vars>
                  </div>
            </div>
            </div>
            
            
            <!--  list related functions: Groups  -->
            <div class="col-4-12">
               <div class="content">
                   
                   <p ng-hide="listCtrl.selectedList"><?php _e('Click any List from left side to see it\'s Groups', 'nm-mailchimp'); ?></p>
                  
                    <div ng-show="listCtrl.selectedList">
                         
                         <list-groups></list-groups>
                  </div>
            </div>
            </div>
        </div>
    
</div>
    
</div> <!-- nm_mailchimp-lists -->


</div>
<script type="text/javascript">
    <!--
    
    var nm_mc_url = '<?php echo $nm_mailchimp -> plugin_meta['url']; ?>';
    (function(){
    
        var mcApp = angular.module('mcListManager', [], function($httpProvider) {
          // Use x-www-form-urlencoded Content-Type
          
          $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
          
           /**
           * The workhorse; converts an object to x-www-form-urlencoded serialization.
           * @param {Object} obj
           * @return {String}
           */ 
          var param = function(obj) {
            var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
        
            for(name in obj) {
              value = obj[name];
        
              if(value instanceof Array) {
                for(i=0; i<value.length; ++i) {
                  subValue = value[i];
                  fullSubName = name + '[' + i + ']';
                  innerObj = {};
                  innerObj[fullSubName] = subValue;
                  query += param(innerObj) + '&';
                }
              }
              else if(value instanceof Object) {
                for(subName in value) {
                  subValue = value[subName];
                  fullSubName = name + '[' + subName + ']';
                  innerObj = {};
                  innerObj[fullSubName] = subValue;
                  query += param(innerObj) + '&';
                }
              }
              else if(value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
            }
        
            return query.length ? query.substr(0, query.length - 1) : query;
          };
        
          // Override $http service's default transformRequest
          $httpProvider.defaults.transformRequest = [function(data) {
            return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
          }];
        });
        
      
        
        mcApp.service('mcList', function(){
            
            var activeListID = '';
            var currentVars = '';
            
            return {
              
              getCurrentVars: function(){
                  return currentVars;
              },
              setCurrentVars: function(v){
                  currentVars = v;
              },
              getActiveListID: function(){
                  return activeListID;
              },
              setActiveListID: function(l){
                  activeListID = l;
              },
              getLoadingImg: function(){
                  return nm_mc_url+'/images/loading.gif';
              }
                
            }
        });
        
        /* ============= directives ============= */
        mcApp.directive('mcList', function(){
           
           return {
               restrict: "E",
               templateUrl: nm_mc_url+"/templates/admin/mc-list.html"
           } 
        });
        
        mcApp.directive('listVars', function(){
           
           return {
               restrict: "E",
               templateUrl: nm_mc_url+"/templates/admin/list-vars.html"
           } 
        });
        
        mcApp.directive('listGroups', function(){
           
           return {
               restrict: "E",
               templateUrl: nm_mc_url+"/templates/admin/list-groups.html"
           } 
        });
        
        
         /* ============= controllers ============= */
        
        mcApp.controller('listController', ['$scope', '$filter', '$http', 'mcList', function($scope, $filter, $http, mcList){
           
           this.selectedList     = false;
           this.currentVars      = {};
           this.showDetail       = false;
           this.detailIcon       = '+';
           this.listIDs          = [];
           this.allVars         = null;
           
            this.showMore = false;
            this.moreIcon = '+';
            
            this.loadingImg = mcList.getLoadingImg();
           
           //I learn/understand t later. :)
           $scope.newVar = {};
           $scope.showVarForm = false;
           $scope.savingVars = false;
           $scope.deletingVars = false;
           $scope.loadingVars = false;
           
           $scope.createForm = false;    //creating subscription form
           
           $scope.currentGroups = {};
           $scope.showGroups = false;
           $scope.moreGroupIcon = '+';
           $scope.savingGroups = {};
           $scope.newGroups = {};
           $scope.savingInterests = {};
           $scope.newInterests = {name: '', groups: []};
           
           $scope.showInterestForm = false;
           
           //subscripiton form
           $scope.selectedInterest = {};
           $scope.selectedVars     = {};
           
           
           
           var thisCtrl = this;
           
           
           $scope.updateInterest = function(idx){
               
               $scope.selectedInterest = $filter('filter')($scope.currentGroups[mcList.getActiveListID()], {checked: true});
           }
           
            $scope.updateVars = function(idx){
               
               //console.log(thisCtrl.currentVars);
               $scope.selectedVars = $filter('filter')(thisCtrl.currentVars, {checked: true});
           }
           
           $scope.generateShortcode = function(){
               
              var meta = {listid: thisCtrl.selectedList.id,
                          vars: $scope.selectedVars,
                          interests: $scope.selectedInterest
                          };
                          
                var send = {form_meta: meta, form_name: $scope.form_name};
                          
              $http.post(ajaxurl+'?action=nm_mailchimp_create_shortcode', send).success(function(data){
                     
                     if(data.status == 'success'){
                         $scope.theShortcode = '[nm-mc-form fid="'+data.form_id+'"]';
                     }
              });
           }
           
           
           
           //populating list data on load
           $http.get(ajaxurl+'?action=nm_mailchimp_get_mc_list').success(function(data){
              //console.log(data);
              
              thisCtrl.total_lists = data.total;
              thisCtrl.allLists    = data.data;
              
              angular.forEach(thisCtrl.allLists, function(list, k){
                 
                 this.push(list.id);
              }, thisCtrl.listIDs);
              
           });
           
           this.toggleDetail = function(){
               
               if(this.showDetail){
                   this.detailIcon = '+';
                   this.showDetail = false;
               }else{
                   this.detailIcon = '-';
                   this.showDetail = true;
               }
           }
           
           this.expandList = function(list){
               
              this.selectedList = list;
              
              //resetting subscription form data
              $scope.selectedInterest = {};
              $scope.selectedVars = {};
              
              //for sharing in other controller
              //mcList. = list;
              
              $scope.loadingVars = true;
              if(this.allVars == null){
                  
                $http.post(ajaxurl+'?action=nm_mailchimp_get_mc_list_vars', {'list_ids': this.listIDs}).success(function(data){
                     
                     //console.log(data); 
                     
                     thisCtrl.allVars = data.data;
                          
                      angular.forEach(thisCtrl.allVars, function(vars, k){
                      if(vars.id === list.id){
                          //setting listID and currentVars to use into another controller
                           mcList.setActiveListID(list.id);
                           mcList.setCurrentVars(vars.merge_vars);
                           thisCtrl.currentVars = vars.merge_vars;
                           
                           $scope.loadingVars = false;
                      }
                  });
                  });
              }else{
                  
                  angular.forEach(this.allVars, function(vars, k){
                      if(vars.id === list.id){
                          //setting listID and currentVars to use into another controller
                           mcList.setActiveListID(list.id);
                           mcList.setCurrentVars(vars.merge_vars);
                           thisCtrl.currentVars = vars.merge_vars;
                           
                           $scope.loadingVars = false;
                      }
                  });
              }
                 
           }
           
           
           
           //NOTE: this function should be under varController but due to nature of module it is here
           this.delVar = function(idx){
               
               $scope.deletingVars = true;
               //delete thisCtrl.currentVars[idx];
               var send = {listid: mcList.getActiveListID(), tag: mcList.getCurrentVars()[idx].tag};
               
               $http.post(ajaxurl+'?action=nm_mailchimp_mc_del_var', send).success(function(data){
                  
                  //console.log(data);
                  if(data.complete)
                      thisCtrl.currentVars.splice(idx, 1);
                  else
                      alert('Error while deleting, please try again.');
                      
                    $scope.deletingVars = false;
              });
              
           }
           
           //NOTE: this function should be under varController but due to nature of module it is here
           this.addVar = function(){
               
                //console.log(mcList.getCurrentVars());
                $scope.savingVars = true;
                var send = {listid: mcList.getActiveListID(), vars: $scope.newVar}
                
                $http.post(ajaxurl+'?action=nm_mailchimp_mc_add_var', send).success(function(data){
                  
                  thisCtrl.currentVars.push($scope.newVar);
                  
                  $scope.newVar = {};
                  $scope.showVarForm = false;
                  $scope.savingVars = false;
                  
              });
           }
           
           
           this.getInterestGrouping = function(list){
                
                //console.log($scope.currentGroups[mcList.getActiveListID()]);
                if( $scope.currentGroups[list.id] === undefined) {
                    $http.post(ajaxurl+'?action=nm_mailchimp_get_mc_list_groups', {'list_id': list.id}).success(function(groups){
                         
                         //console.log(groups);
                         $scope.currentGroups[list.id] = groups;
                         //console.log($scope.currentGroups[mcList.getActiveListID()]);
                    }).error(function(data){
                        //nothing foun
                        $scope.currentGroups[list.id] = null;
                        console.log($scope.currentGroups[list.id]);
                    });     
                    
                }
            }
            
            this.addGroup = function(index, grouping_id){
               
                $scope.savingGroups[grouping_id] = true;
                
                var send = {listid: mcList.getActiveListID(), groupingid: grouping_id, groupname: $scope.newGroups[grouping_id]};
                
                $http.post(ajaxurl+'?action=nm_mailchimp_mc_add_group', send).success(function(data){
                     
                     
                     $scope.savingGroups[grouping_id] = false;
                     $scope.currentGroups[mcList.getActiveListID()][index].groups.push({name: $scope.newGroups[grouping_id]});
                     
                     //console.log($scope.currentGroups[mcList.getActiveListID()]);
                     
                     $scope.newGroups[grouping_id] = null;
                     //$scope.currentGroups[mcList.getActiveListID()] = groups;
                });
            }
            
            this.delGroup = function(parent_index, index, group_name, grouping_id){
                
                //console.log($scope.currentGroups[mcList.getActiveListID()]);
                
                $scope.savingInterests[mcList.getActiveListID()] = true;
                
                var send = {listid: mcList.getActiveListID(), groupname: group_name, groupingid: grouping_id};
                $http.post(ajaxurl+'?action=nm_mailchimp_mc_del_group', send).success(function(data){
                    
                    $scope.currentGroups[mcList.getActiveListID()][parent_index].groups.splice(index, 1);
                    
                    $scope.savingInterests[mcList.getActiveListID()] = false;
                });
            }
            
            
            this.addInterest = function(index, list_id){
               
                $scope.savingInterests[mcList.getActiveListID()] = true;
                
                //console.log($scope.currentGroups[list_id]);
                    
                var send = {listid: mcList.getActiveListID(), type: 'checkboxes', name: $scope.newInterests.name, groups: $scope.newInterests.groups};
                
                //console.log(send);
                $http.post(ajaxurl+'?action=nm_mailchimp_mc_add_interest', send).success(function(data){
                     
                    //console.log(data);
                    $scope.showInterestForm = false;
                    $scope.newInterests = null;
                    $scope.savingInterests[mcList.getActiveListID()] = false;
                    window.location.reload();
                });
            }
            
            this.delInterest = function(index, grouping_id){
                
                $scope.savingInterests[mcList.getActiveListID()] = true;
                $http.post(ajaxurl+'?action=nm_mailchimp_mc_del_interest', {groupingid: grouping_id}).success(function(data){
                    
                    $scope.currentGroups[mcList.getActiveListID()].splice(index, 1);
                    $scope.savingInterests[mcList.getActiveListID()] = false;
                });
            }
           
           
           this.toggleMore = function(){
               
               if(this.showMore){
                   this.moreIcon = '+';
                   this.showMore = false;
               }else{
                   this.moreIcon = '-';
                   this.showMore = true;
               }
           }
           
           this.toggleMoreGroups = function(){
               
               if($scope.showGroups){
                   $scope.moreGroupIcon = '+';
                   $scope.showGroups = false;
               }else{
                   $scope.moreGroupIcon = '-';
                   $scope.showGroups = true;
               }
           }
           
           
           //this is to show form controls when creating form shortocode
           $scope.showFormControls = function(){
               
               if(mcList.getActiveListID() != '')
                   $scope.createForm = true;
                else
                    alert('Select any List first');
           }
           
        }]);
        
        
        
    })();
    
    
    //-->
</script>


