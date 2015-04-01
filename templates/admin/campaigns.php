<?php
/**
 * Mailchimp cAMPAIGNS
 */
 
 global $nm_mailchimp;
 
 //pa_nm_mailchimp($mc_lists);
 ?>

<link rel="stylesheet" type="text/css" href="<?php echo $nm_mailchimp->plugin_meta['url']?>/css/grid/simplegrid.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $nm_mailchimp->plugin_meta['url']?>/css/angularcss/animations.css" />
<div id="ajax-loader"></div>
<div id="alertbox" class="alert"></div>
<div class="wrap" ng-app="mcCampaignManager">
  <div id="nm_mailchimp-campaigns" ng-controller="campaignController as camCtrl">
    <h2>
      <?php _e("Mailchimp Campaigns", 'nm-mailchimp');?>
      <span style="color:red"><?php _e('It is PRO Feature', 'nm-mailchimp');?></span>
    </h2>

    <div class="grid">
        
        <div class="col-6-12">
            <div class="content">
                <h3><?php _e("Total campaigns found: ", 'nm-mailchimp');?> {{total_campaigns}}</h3>
            </div>
        </div>
        <div class="col-6-12">
            <div class="content">
                <button ng-click="createCampaign()" ng-hide="createForm" class="button button-primary"><?php _e('Create Campaign', 'nm-mailchimp');?></button>
                <p ng-show="createForm"><?php _e( 'Please fill below form to create campaign.', 'nm-mailchimp' ); ?></p>
            </div>
        </div>
    </div>

    <div class="grid" style="margin-top:15px;">
        <div ng-class="{ 'col-6-12': !createForm , 'col-4-12': createForm }" class="animate-enter">
            
               <div class="content">
                   <h3> <span class="dashicons dashicons-list-view"></span> <?php _e('Mailchimp Campaigns Lists', 'nm-mailchimp'); ?> </h3>
                   
                   <table class="wp-list-table fixed widefat">
                       <thead>
                            <tr>
                                <th>Names</th>
                                <th>Date sent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(key, data) in camp_data" ng-class-even="'alternate'">
                               <td>{{data.title}}</td>
                               <td>
                                    <small ng-if="data.status == 'save'"><?php _e( 'Not sent!', 'nm-mailchimp' ); ?></small>
                                    <small ng-if="data.status == 'sending'"><?php _e( 'Sending...', 'nm-mailchimp' ); ?></small>
                                    <small>{{data.send_time | date:'medium'}}</small>
                                    <a ng-if="data.status == 'sent'" href title="<?php _e( 'Show Details', 'nm-mailchimp' ); ?>" ng-click="showGraph(key)"><span style="float: right; margin-left: 5px;" class="dashicons dashicons-chart-bar"></span></a>
                                    <a ng-if="data.status == 'save'" href title="<?php _e( 'Send Now', 'nm-mailchimp' ); ?>" ng-click="sendCampaignAll(data.id)"><span style="float: right; margin-left: 5px;" class="dashicons dashicons-email-alt"></span></a>
                                    <a ng-if="data.status == 'save'" href title="<?php _e( 'Test Send', 'nm-mailchimp' ); ?>" ng-click="sendCampaignTest(data.id)"><span style="float: right; margin-left: 5px;" class="dashicons dashicons-admin-tools"></span></a>
                                    <a ng-if="data.status == 'save'" href title="<?php _e( 'Delete it', 'nm-mailchimp' ); ?>" ng-click="deleteCampaign(data.id)"><span style="float: right; margin-left: 5px;" class="dashicons dashicons-trash"></span></a>
                                </td>
                            </tr>
                        </tbody>
                   </table>
                   
               </div>            
        </div>
        <div ng-class="{ 'col-6-12': !createForm , 'col-8-12': createForm  }" class="animate-enter">
            <div class="content">
                <div id="nm_chart" ng-show="showChart" style="width: 540px; height: 280px;"></div>
                <div id="nm_camp_stats" ng-show="showChart">
                    <table>
                        <tr>
                            <td><?php _e( 'Send from Name', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_from_name"></td>
                            <td><?php _e( 'Send from Email', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_from_email"></td>
                        </tr>
                        <tr class="alternate">
                            <td><?php _e( 'Hard bounces', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_hard_bounces"></td>
                            <td><?php _e( 'Soft bounces', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_soft_bounces"></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'Opened unique/total', 'nm-mailchimp' ); ?></td>
                            <td><span ng-bind="cam_unique_opens"></span>/<span ng-bind="cam_total_opens"></span></td>
                            <td><?php _e( 'Last open', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_last_open"></td>
                        </tr>
                        <tr class="alternate">
                            <td><?php _e( 'Forwarded', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_forwarded"></td>
                            <td><?php _e( 'Forwarded opens', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_forwarded_opens"></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'Total clicks', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_total_clicks"></td>
                            <td><?php _e( 'Last click', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_last_click"></td>
                        </tr>
                        <tr class="alternate">
                            <td><?php _e( 'Facebook likes', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_fb_likes"></td>
                            <td><?php _e( 'Successful deliveries', 'nm-mailchimp' ); ?></td>
                            <td ng-bind="cam_success_deliver"></td>
                        </tr>
                    </table>
                </div>
                <div ng-show="createForm" id="create-campaign-form">
                    <div ng-switch="step">

                        <div ng-switch-when="1">
                            <div class="header text-center" ng-init="clickOnText = false; clickOnImage = false">
                                <p ng-hide="clickOnText"><?php _e( 'Template Header', 'nm-mailchimp' ); ?></p>
                                <p ng-show="clickOnText"><?php _e( 'Enter Text (You can also use HTML tags)', 'nm-mailchimp' ); ?></p>
                                <p ng-show="clickOnText"><textarea ng-model="template.headerText"></textarea></p>
                                <button ng-click="clickOnImage = true" class="button-secondary upload_image_button" data-title="<?php _e( 'MailChimp Email Header Image', 'nm-mailchimp' ); ?>" data-btntext="<?php _e( 'Insert It', 'nm-mailchimp' ); ?>"><?php _e( 'Image', 'nm-mailchimp' ); ?></button>
                                <button ng-click="clickOnText = true" class="button-secondary"><?php _e( 'Text', 'nm-mailchimp' ); ?></button>
                                <p ng-show="clickOnImage"><?php _e( 'Enter Image url or use from media.', 'nm-mailchimp' ); ?></p>
                                <input ng-show="clickOnImage" type="text" id="header-image" ng-model="template.headerImage">
                                <div id="header-image-prev">
                                    <img ng-src="{{template.headerImage}}" width="100%">
                                </div>
                            </div>
                            <div class="section text-center">
                                <p><?php _e( 'Select Campaign Contents from Your Page/Post', 'nm-mailchimp' ); ?></p>
                                <select ng-model="template.content">
                                     <?php
                                     global $post;
                                     $args = array( 'posts_per_page' => 100, 'post_type' => array( 'post', 'page' ) );
                                     $posts = get_posts($args);
                                     if($posts){
	                                     foreach( $posts as $post ) : setup_postdata($post);
	                                        echo '<option value="'.$post->ID.'">'. get_the_title().'</option>';
	                                     endforeach;
	                                 }
	                                 ?>
                                </select>                                 
                            </div>
                            <div class="footer text-center">
                                <p><?php _e( 'Template Footer (You can also use HTML tags)', 'nm-mailchimp' ); ?></p>
                                <textarea rows="2" ng-model="template.footer"></textarea>
                            </div>
                            <hr>

                            <div class="text-center">
                                <button class="button-primary" ng-click="previewTemplate()">Preview</button>                               
                                <button class="button-secondary" ng-click="cancelCampaign()">Cancel</button>
                            </div>
                        </div>


                        <div ng-switch-when="2">
                            <div id="template-preview" contenteditable="true">
                                <header>
                                    <img ng-src="{{template.headerImage}}" width="100%">
                                    <div ng-bind-html="emailHeader()" style="text-align: center; padding: 10px; border-bottom: 1px solid #eee;"></div>
                                </header>
                                <section ng-bind-html="emailContent()"></section>
                                <footer ng-bind-html="emailFooter()" style="text-align: center; padding: 10px; border-top: 1px solid #eee;"></footer>
                            </div>
                            <hr>
                            <div class="buttons" style="text-align: center;">
                                <button class="button-secondary" ng-click="setStep(1)"><?php _e( 'Back', 'nm-mailchimp' ); ?></button>
                                <button class="button-secondary" ng-click="cancelCampaign()"><?php _e( 'Cancel', 'nm-mailchimp' ); ?></button>
                                <button class="button-primary" ng-click="setStep(3)"><?php _e( 'Next', 'nm-mailchimp' ); ?></button>
                            </div>
                        </div>


                        <div ng-switch-when="3" ng-form="createCamForm">
                            <div>
                                <table>
                                    <tr>
                                        <td style="width: 25%;"><label><?php _e( 'Select List', 'nm-mailchimp' ); ?></label></td>
                                        <td>
                                            <select ng-model="nmCam.list_id" ng-options="list.id as list.name for list in mc_lists" required></select>
                                            <p class="description"><?php _e( 'Select the list to send this campaign (required)', 'nm-mailchimp' ); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Campaign Subject', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.subject" type="text" required>
                                        <p class="description">Subject line for your campaign message (required)</p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'From Email', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.from_email" type="email" ng-init="nmCam.from_email='<?php bloginfo( 'admin_email' ); ?>'" required>
                                        <p class="description"><?php _e( 'Sender Email (required)', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'From Name', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.from_name" type="text" ng-init="nmCam.from_name='<?php bloginfo( 'name' ); ?>'" required>
                                        <p class="description"><?php _e( 'Sender Name (required)', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                    <tr>
                                        <td><label><?php _e( 'Title', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.title" type="text">
                                        <p class="description"><?php _e( 'An internal name to use for this campaign. By default, the campaign subject will be used', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Tracking', 'nm-mailchimp' ); ?></label></td>
                                        <td>
                                            <label><input ng-model="nmCam.tracking.opens" type="checkbox"><?php _e( 'Open', 'nm-mailchimp' ); ?></label>
                                            <label><input ng-model="nmCam.tracking.html_clicks" type="checkbox"><?php _e( 'HTML Click', 'nm-mailchimp' ); ?></label>
                                            <label><input ng-model="nmCam.tracking.text_clicks" type="checkbox"><?php _e( 'Text Click', 'nm-mailchimp' ); ?></label>
                                            <p class="description"><?php _e( 'Set which recipient actions will be tracked, as a struct of boolean values with the following keys: "opens", "html_clicks", and "text_clicks". By default, opens and HTML clicks will be tracked. Click tracking can not be disabled for Free accounts', 'nm-mailchimp' ); ?></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Authenticate', 'nm-mailchimp' ); ?></label></td>
                                        <td><label><input ng-model="nmCam.authenticate" type="checkbox"><?php _e( 'Authenticate', 'nm-mailchimp' ); ?></label>
                                        <p class="description"><?php _e( 'Set to true to enable SenderID, DomainKeys, and DKIM authentication, defaults to false', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Analytics Google', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.analytics.google" type="text">
                                        <p class="description"><?php _e( 'For Google Analytics tracking', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Generate Text', 'nm-mailchimp' ); ?></label></td>
                                        <td><label><input ng-model="nmCam.generate_text" type="checkbox">Generate Text</label>
                                        <p class="description"><?php _e( 'Whether of not to auto-generate your Text content from the HTML content. Note that this will be ignored if the Text part of the content passed is not empty, defaults to false', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Auto Tweet', 'nm-mailchimp' ); ?></label></td>
                                        <td><label><input ng-model="nmCam.auto_tweet" type="checkbox"><?php _e( 'Auto Tweet', 'nm-mailchimp' ); ?></label>
                                        <p class="description"><?php _e( 'If set, this campaign will be auto-tweeted when it is sent - defaults to false. Note that if a Twitter account isn\'t linked, this will be silently ignored', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Auto Facebook Post', 'nm-mailchimp' ); ?></label></td>
                                        <td><input ng-model="nmCam.auto_fb_post" type="text">
                                        <p class="description"><?php _e( 'Facebook pages IDs each fb page id separated by comma', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Facebook Comments', 'nm-mailchimp' ); ?></label></td>
                                        <td><label><input ng-model="nmCam.fb_comments" type="checkbox"><?php _e( 'Facebook Comment', 'nm-mailchimp' ); ?></label>
                                        <p class="description"><?php _e( 'If true, the Facebook comments (and thus the archive bar will be displayed. If false, Facebook comments will not be enabled (does not imply no archive bar, see previous link). Defaults to "true"', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                    <tr>
                                        <td><label><?php _e( 'Ecomm360', 'nm-mailchimp' ); ?></label></td>
                                        <td><label><input ng-model="nmCam.ecomm360" type="checkbox"><?php _e( 'Ecomm360', 'nm-mailchimp' ); ?></label>
                                        <p class="description"><?php _e( 'If set, our Ecommerce360 tracking will be enabled for links in the campaign', 'nm-mailchimp' ); ?></p></td>
                                    </tr>
                                </table>
                            </div>
                            <hr>
                            <div class="buttons" style="text-align: center;">
                                <button class="button-secondary" ng-click="setStep(2)"><?php _e( 'Back', 'nm-mailchimp' ); ?></button>
                                <button class="button-secondary" ng-click="cancelCampaign()"><?php _e( 'Cancel', 'nm-mailchimp' ); ?></button>
                                <a class="button-primary" href="http://najeebmedia.com/wordpress-plugin/wordpress-mailchimp-subscription-form-create-manage-campaigns/" target="_blank" ng-disabled="createCamForm.$invalid"><?php _e( 'Upgrade to PRO Version', 'nm-mailchimp' ); ?></a>
                                <p><?php _e( 'You need to Upgrade PRO version to create Campaigns.', 'nm-mailchimp' ); ?></p>
                            </div>
                        </div> <!-- switch 3 -->
                        
                        
                    </div> <!-- step -->                   
                </div>
                
            </div>
        </div>
        
    </div> <!-- grid -->

  </div>
</div>
<script type="text/javascript">

<!--
    (function() {
        var mcApp = angular.module('mcCampaignManager', [], function($httpProvider) {
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
        
        mcApp.controller('campaignController', ['$scope','$http', '$sce', function($scope, $http, $sce){            

            //populating Campaigns
            $scope.total_campaigns = 0;

            $scope.template = {}; 
            $scope.nmCam = {}; 

            //loading all campaigns and lists
            $http.get(ajaxurl+'?action=nm_mailchimp_get_mc_campaigns_lists').success(function(data){
              
                $scope.total_campaigns = data.campaigns.total;
                $scope.camp_data = data.campaigns.data;
                
                $scope.mc_lists = data.lists.data;

            });
            
            $scope.showChart = false;

            $scope.createCampaign = function(){
                $scope.showChart = false;
                $scope.createForm = true;
            }

            $scope.cancelCampaign = function(){
                $scope.createForm = false;
            }

            $scope.step = 1;

            $scope.setStep = function(step){
                $scope.step = step;
                if (step = 3) {
                    $scope.completeTemplate = jQuery('#template-preview').html();
                };
            }

            $scope.showGraph = function(id){
                jQuery('#ajax-loader').show();

                $scope.cam_from_name = $scope.camp_data[id].from_name;
                $scope.cam_from_email = $scope.camp_data[id].from_email;
                $scope.cam_hard_bounces = $scope.camp_data[id].summary.hard_bounces;
                $scope.cam_soft_bounces = $scope.camp_data[id].summary.soft_bounces;
                $scope.cam_total_opens = $scope.camp_data[id].summary.opens;
                $scope.cam_last_open = $scope.camp_data[id].summary.last_open;
                $scope.cam_forwarded = $scope.camp_data[id].summary.forwards;
                $scope.cam_forwarded_opens = $scope.camp_data[id].summary.forwards_opens;
                $scope.cam_total_clicks = $scope.camp_data[id].summary.clicks;
                $scope.cam_last_click = $scope.camp_data[id].summary.last_click;
                $scope.cam_fb_likes = $scope.camp_data[id].summary.facebook_likes;
                $scope.cam_success_deliver = $scope.camp_data[id].summary.emails_sent;
                $scope.cam_unique_opens = $scope.camp_data[id].summary.unique_opens;


                // Google Chart
                $scope.showChart = true;
                $scope.createForm = false;

                jQuery.ajax({
                      url: 'https://www.google.com/jsapi?callback',
                      cache: true,
                      dataType: 'script',
                      success: function(){
                        google.load('visualization', '1', {packages:['corechart'], 'callback' : function(){

                            var data = new google.visualization.arrayToDataTable([
                              ['title', 'Statistics'],
                              ["Opens", $scope.camp_data[id].summary.unique_opens],
                              ["Clicks", $scope.camp_data[id].summary.clicks],
                              ["Unsubscribers", $scope.camp_data[id].summary.unsubscribes],
                              ["Others", $scope.camp_data[id].summary.emails_sent - $scope.camp_data[id].summary.unique_opens],
                            ]);

                            var options = {
                                    title: $scope.camp_data[id].title + ', Sent to: '+ $scope.camp_data[id].summary.emails_sent,
                                    is3D: true,
                                };

                            var chart = new google.visualization.PieChart(document.getElementById('nm_chart'));
                            chart.draw(data, options);

                          }
                        });
                      }
                });
                jQuery('#ajax-loader').hide();  
            }

            $scope.previewTemplate = function(){
                jQuery('#ajax-loader').show();

                $http.post(ajaxurl, {action: 'nm_mailchimp_template_preview'  , contentid: $scope.template.content}).
                    success(function(resp) {
                        $scope.emailContent = function() {
                            return $sce.trustAsHtml('<h1>'+ resp.post_title + '</h1>' + resp.post_content);
                        };
                        $scope.emailHeader = function() {
                            return $sce.trustAsHtml($scope.template.headerText);
                        }; 
                        $scope.emailFooter = function() {
                            return $sce.trustAsHtml($scope.template.footer);
                        };                  
                    $scope.step = 2;
                    jQuery('#ajax-loader').hide();
                });                  
            }

            $scope.saveCampaign = function(){
                jQuery('#ajax-loader').show();
                $scope.createCampaignData = {
                    type : "regular",
                    options : $scope.nmCam,
                    content : {
                        "html": $scope.completeTemplate,
                        "text": $scope.completeTemplate
                    }
                }

                $http.post(ajaxurl, {action: 'nm_mailchimp_create_campaign'  , campaign_data: $scope.createCampaignData}).
                    success(function(resp) {
                        //Load campaigns again
                        $http.get(ajaxurl+'?action=nm_mailchimp_get_mc_campaigns_lists').success(function(data){
                          
                            $scope.total_campaigns = data.campaigns.total;
                            $scope.camp_data = data.campaigns.data;
                            
                            $scope.mc_lists = data.lists.data;

                        });
                        jQuery('#ajax-loader').hide();                       
                        jQuery('#alertbox').addClass('alert-success').html('<strong>Campaign Created!</strong> Campaign is successfully created.').show();
                        setTimeout(function() {jQuery('#alertbox').fadeOut('slow')}, 3000);
                        $scope.createForm = false;
                        $scope.step = 1;
                        $scope.nmCam = {};
                        $scope.template = {};
                }).
                    error(function() {
                        jQuery('#ajax-loader').hide();                       
                        jQuery('#alertbox').addClass('alert-warning').html('<strong>Error!</strong> Please fill required fields.').show();
                        setTimeout(function() {jQuery('#alertbox').fadeOut('slow')}, 3000);
                });                
            }

            //Delete Campaign

            $scope.deleteCampaign = function(id){
                if (confirm('Are you sure you want to delete this Campaign?')) {
                    jQuery('#ajax-loader').show();
                    $http.post(ajaxurl, {action: 'nm_mailchimp_delete_campaign'  , campaign_id: id}).
                        success(function(resp) {

                            //Loading Lists again
                            $http.get(ajaxurl+'?action=nm_mailchimp_get_mc_campaigns_lists').success(function(data){
                              
                                $scope.total_campaigns = data.campaigns.total;
                                $scope.camp_data = data.campaigns.data;
                                
                                $scope.mc_lists = data.lists.data;

                            });
                            jQuery('#ajax-loader').hide();
                            jQuery('#alertbox').addClass('alert-success').html('<strong>Campaign Deleted!</strong> Campaign is successfully deleted.').show();
                            setTimeout(function() {jQuery('#alertbox').fadeOut('slow')}, 3000);
                    });
                }                    
            }

            //Campaign send all

            $scope.sendCampaignAll = function(id){
                if (confirm('Do you want to send this right now?')) {
                    $http.post(ajaxurl, {action: 'nm_mailchimp_send_campaign'  , campaign_id: id}).
                        success(function(resp) {

                            //Loading Lists again
                            $http.get(ajaxurl+'?action=nm_mailchimp_get_mc_campaigns_lists').success(function(data){
                              
                                $scope.total_campaigns = data.campaigns.total;
                                $scope.camp_data = data.campaigns.data;
                                
                                $scope.mc_lists = data.lists.data;

                            });                        
                            jQuery('#alertbox').addClass('alert-success').html('<strong>Campaign Sent!</strong> Campaign sent to list.').show();
                            setTimeout(function() {jQuery('#alertbox').fadeOut('slow')}, 3000);
                    });
                }
            }

            //Test Send

            $scope.sendCampaignTest = function(id){
                jQuery('#ajax-loader').show();
                $http.post(ajaxurl, {action: 'nm_mailchimp_send_campaign_test'  , campaign_id: id}).
                    success(function(resp) {
                        jQuery('#ajax-loader').hide();
                        jQuery('#alertbox').addClass('alert-success').html('<strong>Campaign Sent!</strong> Campaign sent to admin email.').show();
                        setTimeout(function() {jQuery('#alertbox').fadeOut('slow')}, 3000);
                });
            }
        }]);

        // WP Media Uploader
        var nm_mailhimp_media_uploader;
         
        jQuery('.upload_image_button').live('click', function( event ){
         
            event.preventDefault();

            // Create the media frame.
            nm_mailhimp_media_uploader = wp.media.frames.nm_mailhimp_media_uploader = wp.media({
              title: jQuery( this ).data( 'title' ),
              button: {
                text: jQuery( this ).data( 'btntext' ),
              },
              multiple: false  // Set to true to allow multiple files to be selected
            });
         
            // When an image is selected, run a callback.
            nm_mailhimp_media_uploader.on( 'select', function() {
              // We set multiple to false so only get one image from the uploader
              attachment = nm_mailhimp_media_uploader.state().get('selection').first().toJSON();
                
                
                 // jQuery('#header-image-prev').html('<img src="'+attachment.url+'" width="100%">');
                 jQuery('#header-image').val(attachment.url);
                 jQuery('#header-image').trigger('input');
            });
         
            // Finally, open the modal
            nm_mailhimp_media_uploader.open();
        });

    })();
      
    //-->
</script>