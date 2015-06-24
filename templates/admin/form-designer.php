<?php 
global $nm_mailchimp;
$options = get_option( 'nm_mailchimp_form_settings' );
$newData = json_encode($options);
?>
<style>
.content {
 background: #E7E7E7;
    border: 1px solid #C8C8C8;
  border-radius: 4px;
  text-align: center;
    margin-top: 20px;
}	
.nm-inner {
	padding: 10px;
}
.wrapper-form{
	background: rgba(255,255,255,.9);
	width: 250px;
	margin: 0 auto;
	  box-shadow: 0 0 20px rgba(0,0,0,.3);
	  outline: none;
	margin-top: 25px;
}
#live-form {
	width: 100%;
 padding: 20px 20px 20px 15px;
 border: 0;
}
.header-text{
	text-align: center;
  padding: 2px 2px;
  background: rgba(248,248,248,.9);
  font-size: 20px;
  font-weight: 300;
  color: #232323;
  border-bottom: 1px solid rgba(0,0,0,.1);
  height: 76px;
}
.footer-text{
	text-align: center;
  padding: 2px 2px;
  background: rgba(248,248,248,.9);
  font-size: 20px;
  font-weight: 300;
  color: #232323;
  border-top: 1px solid rgba(0,0,0,.1);
    display: block;
      padding: 14px 54px 15px;
   height: 76px;
}
.header-text h3{
	color: #fff;
}
button{

  overflow: hidden;
  margin: 0 auto; 	
   padding: 6px; 
  outline: none;
   border: 0;
  border-radius: 0;
   /*font: 300 15px/39px Helvetica, Arial, sans-serif; */
  text-decoration: none;
   color: #fff; 
  cursor: pointer;
  text-shadow: none;
  background: none;
  -webkit-box-shadow: none;
  box-shadow: none;
  text-transform: none;
  cursor: pointer;
  background-color: #2da5da;
  opacity: 0.8;
};
.hover-btn:hover{
    background: #fff;
}
input[type="text"] {
	display: block;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
  width: 100%;
  height: 39px;
  height: 35px; 
  padding: 5px 7px;
  border-color: #e5e5e5;
  outline: none;
  border-width: 2px;
  border-style: solid;
  border-radius: 0;
  background: #fff;
  font: 15px/19px Helvetica, Arial, sans-serif;
  color: #404040;
  appearance: normal;
  -moz-appearance: none;
  -webkit-appearance: none;
}
.btn-div{
	width: 100%;
}
table {
  border: 1px solid #C8C8C8;
  padding: 10px;
  border-radius: 4px;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo $nm_mailchimp->plugin_meta['url']?>/css/grid/simplegrid.css" />
<div class="wrap" ng-app="mcFormDesigner">
	<div id="mc-form-designer" ng-controller="mcDesignerCtrl">
		<h2>
			<?php _e("Mailchimp Form Designer", 'nm-mailchimp');?>
		</h2>
		<div class="grid">
			<div class="col-6-12">
				<div class="content">
					<h3><?php _e("Live Preview", 'nm-mailchimp');?></h3>
					<!-- LIVE PREIVEIW -->
				</div>

					<div class="wrapper-form" style="transition: width 2s; width: {{subForm.fromWidth}}%;">
						<div class="form-container">
							<div class="header-text" style="background-color: {{subForm.headerBg}};">
								<h4  style="color: {{subForm.headerTextColor}};">{{subForm.headerText}}</h4>
							</div>
							<div style="background-color:{{subForm.sectionBackgroundColor}};">
								
							<table id="live-form">

								<tr>
									<td><label for=""></label></td>
									<td><input type="text"  placeholder="Your Name"></td>
								</tr>
								<tr>
									<td><label for=""></label></td>
									<td><input type="text" placeholder="Email Address"></td>
								</tr>
								<tr>
									<td><div></div></td>
								</tr>
							</table>
                            </div>
							<div class="footer-text" style="background-color: {{subForm.footerBg}};">
								
								<button style="color: {{subForm.buttonFontColor}}; background-color: {{subForm.btnBackground}};">{{subForm.buttonlable}}</button>	
									

							</div>
						</div>
					</div>
			</div>
			<div class="col-6-12">
				<div class="content">
					<h3><?php _e("Form Styling", 'nm-mailchimp');?></h3>
				</div>
				<div class="nm-inner">
					<table style="width:100%; ">
						<tr>
							<td><?php _e( 'Form Width', 'nm-mailchimp' ); ?></td>
							<td><input style="width: 100%;" type="range"  ng-model="subForm.fromWidth" name="points" min="30" max="100"></td>
						</tr>
						<tr>
							<h3 class="section-headings ">Header Section</h3>
						</tr>
						<tr>
							<td><label for="heading"><?php _e( 'Header Text', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" id="heading" ng-model="subForm.headerText"></td>
						</tr>
						<tr>
							<td><label for="headerBg"><?php _e( 'Header Background Color', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" class="colorpicker" id="headerBg" value="<?php echo $options['headerBg'] ?>" ng-model="subForm.headerBg"></td> 
						</tr>
						<tr>
							<td><label for="headerTextColor"><?php _e( 'Font Color', 'nm-mailchimp' ); ?></label></td>
							<td> <input type="text" class="colorpicker" id="headerTextColor" value="<?php echo $options['headerTextColor'] ?>" ng-model="subForm.headerTextColor"></td>
						</tr>
					</table>

					<h3>Fields Section</h3>
					<table style="width:100%; ">
						<tr>
							<td><label for="sectionBackgroundColor"><?php _e( 'Section Background', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" class="colorpicker" id="sectionBackgroundColor" value="<?php echo $options['sectionBackgroundColor'] ?>" ng-model="subForm.sectionBackgroundColor"></td> 
						</tr>
					</table>
 	 
					<h3>Footer Section</h3>
					<table style="width:100%; ">
						<tr>
							<td><label for="footertext"><?php _e( 'Button Lable Text', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" id="footertext" ng-model="subForm.buttonlable"></td>
						</tr>
						<tr>
							<td><label for="buttonFontColor"><?php _e( 'Button Lable Font Color', 'nm-mailchimp' ); ?></label></td>
							<td> <input type="text" class="colorpicker" id="buttonFontColor" value="<?php echo $options['buttonFontColor'] ?>" ng-model="subForm.buttonFontColor"></td>
						</tr>
						<tr>
							<td><label for="btnBackground"><?php _e( 'Button Background Color', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" class="colorpicker" value="<?php echo $options['btnBackground'] ?>" id="btnBackground" ng-model="subForm.btnBackground"></td> 
						</tr>						
						<tr>
							<td><label for="footerBg"><?php _e( 'Footer Background Color', 'nm-mailchimp' ); ?></label></td>
							<td><input type="text" class="colorpicker" value ="<?php echo $options['footerBg'] ?>" id="footerBg" ng-model="subForm.footerBg"></td> 
						</tr>
					</table>
					<p  style="text-align: center;">
					<button class="button-primary" ng-click="saveFormDesigner()">Save</button>
					<img ng-show="loadingimage" src="<?php echo $this->plugin_meta['url'] . 'images/loading.gif' ?>" alt="loading">
					</p>
					<div ng-show="savedMessage" class="updated settings-error notice">
					    <p><strong><?php _e( 'Settings saved', 'nm-mailchimp' ); ?>.</strong></p>
					</div>	
				</div>			
			</div>
		</div>	
	</div>
</div>
<script type="text/javascript">
<!--
angular.module('mcFormDesigner', [], function($httpProvider) {
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
    }).controller('mcDesignerCtrl', ['$scope', '$http', function($scope,$http){
	$scope.loadingimage = false;
	$scope.savedMessage = false;
	$scope.subForm = <?php echo ($newData) ? $newData : '{"headerText": "SignUp Form", "buttonlable": "Register Now"}' ; ?>

	jQuery('.colorpicker').wpColorPicker({
		change: function(event, ui){
			var currentEle = jQuery(this).attr('id');
			$scope.$apply(function(){
				$scope.subForm[currentEle] = ui.color.toString();
			});
	},
		clear: function(){
			$scope.$apply(function(){
				$scope.subForm.currentEle = '';
			});
		}
	});

	$scope.saveFormDesigner = function(){
		$scope.loadingimage = true;
		$scope.savedMessage = false;
		$http.post(ajaxurl, {action: 'nm_mailchimp_form_designer'  , formData: $scope.subForm}).
            success(function(resp) {
            $scope.loadingimage = false;	
            $scope.savedMessage = true;
           
        });		
	}
}]);

//--></script>