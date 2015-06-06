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
    
    
 </style>
 
 
<div class="wrap" ng-app="mcListManager">

<div id="nm_mailchimp-forms" ng-controller="mcFormsController as formCtrl">
 <h2>
	<?php _e("Subscription Forms", 'nm-mailchimp');?>
	<a href="<?php echo admin_url('admin.php?page=nm_mailchimp_lists');?>" class="button"><?php _e('Create Form', 'nm-mailchimp');?></a>
</h2>

<p>
    <?php _e('Copy shortcode below and paste into anywhere in Page or Post. Widgets can also be dragged of these Forms.', 'nm-mailchimp'); ?>
</p>

<table class="wp-list-table fixed widefat">
               <thead>
                   <tr>
                       <th>Form ID</th>
                       <th>Form Name <a href ng-click="formCtrl.toggleForms()">[{{moreFormIcon}}]</a></th>
                       <th>Shortcode</th>
                       <th><?php _e('Remove', 'nm-mailchimp');?></th>
                </tr>
                </thead>
<tbody>
                <tr ng-repeat="form in forms" ng-class-even="'alternate'">
                       <td>{{form.form_id}}</td>
                       <td><strong>{{form.form_name}}</strong>
                       <span ng-show="showFormDetail">
                           <ul  ng-init="parseVars(form.form_meta)">
                              <li ng-repeat="var in meta.vars">
                                  {{var.name}} ({{var.tag}})
                              </li>
                           </ul>
                           
                       </span>
                       </td>
                       <td><pre>[nm-mc-form fid="{{form.form_id}}"]</pre></td>
                       <td><a ng-click="removeForm($index, form.form_id)"><span class="dashicons dashicons-no"></span></a></td>
                </tr>
</tbody>
</table>
                
                           
                

</div>  <!-- nm_mailchimp-forms -->

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
       
       /* ============= controllers ============= */
        
        // mcForms
        mcApp.controller('mcFormsController', ['$scope', '$http',  function($scope, $http){
           
           $scope.moreFormIcon = '+';
           $scope.showFormDetail = false;
           
           
           //populating forms data on load
           $http.get(ajaxurl+'?action=nm_mailchimp_get_forms').success(function(forms){
              
              $scope.forms = forms;
              console.log(forms);
           });
           
           this.toggleForms = function(){
               
               if($scope.showFormDetail){
                   $scope.moreFormIcon = '+';
                   $scope.showFormDetail = false;
               }else{
                   $scope.moreFormIcon = '-';
                   $scope.showFormDetail = true;
               }
           }
           
           $scope.removeForm = function(index, id){
               
               var a = confirm('Are you sure?');
               if(a){
                   $http.post(ajaxurl+'?action=nm_mailchimp_remove_form', {formid: id}).success(function(resp){
                      $scope.forms.splice(index, 1);
                   });     
               }
              
           }
           
           $scope.parseVars = function(meta){
               
               $scope.meta = JSON.parse(meta);
               //console.log($scope.meta.vars);
           }
            
        }]);
        
        
        
    })();
    
    
    //-->
</script>


