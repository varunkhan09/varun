<?php 
    session_start();
    include_once $_SERVER['DOCUMENT_ROOT']."/global_variables.php"; 
?>
<style>
    .div-center{
        width: 80%;
        position: absolute;
        top:0;
        bottom: 0;
        left: 0;
        right: 0;
        margin: auto;              
    }

    .form-item {
        margin-top: 10%;

    }

    .file-upload > center > a{
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
    }

    .divider{
        color:#00B2EE;
        margin:20px auto;
        overflow:hidden;
        text-align:center;   
        line-height:1.2em;
        font-size: 14px;
        font-weight: 700;
    }

    .divider:before, .divider:after{
        content:"";
        vertical-align:top;
        display:inline-block;
        width:50%;
        height:0.65em;
        border-bottom:1px dashed #00B2EE;
        margin:0 2% 0 -55%;
    }
    .divider:after{
        margin:0 -55% 0 2%;
    }
    input.btn.btn-default {
        width: 175px;
    }
    .message{
      margin-top:110px;
      padding:1px 15px;
    }
</style>


<script type="text/javascript">
    $(document).ready(function() {
        new multiple_file_uploader({
            form_id: "fileUpload",
            autoSubmit: true,
            server_url: "dzone/uploader.php" // PHP file for uploading the browsed files
        });
    });
</script>


<div ng-controller="DZoneParentCtrl">
        <!--     <div class="message div-center">
                <alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)"><strong>{{alert.msgtag}}</strong>{{alert.msg}}</alert>
            </div>

 -->

<div ng-controller="DeliveryZoneCtrl">
    <div class="col-xs-6 col-sm-4 div-center form-item">
        <div class="panel panel-default">

            <div class="panel-heading text-center">
                <strong class="headingname">Delivery Zone</strong>
            </div>

            <div class="panel-body">

                <h6 style="color:#FA5858">
                    Note: To import pin code, download template file [.xls] and upload with record.
                    <a style="float:right; text-decoration:none;" href="template/Pincode Template.xlsx" download><span class="glyphicon glyphicon-download"></span> Pincode Template </a>
               
                </h6>

              
                

                <!-- For Pin code Upload Task-->           
                <div style="margin-bottom:50px;">
                    <div class="upload_box">
                        <form name="fileUpload" id="fileUpload" action="javascript:void(0);" enctype="multipart/form-data">
                            <div style="margin-left:10%;" class="file_browser">
                                <input type="file" name="multiple_files[]" id="_multiple_files" class="hide_broswe" />
                            </div>

                            <div style="margin-left:10%;" class="file_upload">
                                <input type="submit" value="Upload" class="upload_button" />
                            </div>
                        </form>
                    </div>  
                    <div class="file_boxes"></div>
                    <span id="removed_files"></span>
                </div>

                <!-- Pin code Upload Task end here  -->  


                <!-- FOr Download Link Here -->  
               <!--  <div class="form-group">
                    <center>
                        <a href="template/Pincode Template.xlsx" download>Excel Template Download </a>
                    </center>
                </div> -->


                <!-- For divider & Start Here -->

                <div class="form-group">
                    <h1 class="divider">OR</h1>
                </div>

                <!-- divider Over Here -->

                <!-- For pin code Tag Input-->

                <div ng-controller="ZonePincodeCtrl">
                    
                    <h6 style="color:#FA5858">
                        Note: To add pincode into text box enter code and press enter key.

                        <a style="float:right; text-decoration:none;" ng-click="show_pincode_list()" href="javascript:void(0)"><span class="{{show_class}}"></span> {{show_pincode_text}} </a>
               
                    </h6>


                    <div ng-show="show_pincode" class="form-group">
                       <textarea style="font-weight:bold;color:#000;" readonly ng-model="pincode_list" class="form-control" rows="3"></textarea>
                    </div>


                    <form class="form-horizontal" role="form" ng-submit="submitPincode()">
                        <div class="form-group">
                            <tags-input style="margin:5px;padding:5px 10px;"
                                        ng-model="tags" 
                                        placeholder="Enter Pincode" 
                                        min-length="6" 
                                        max-length="6" 
                                        numbers-only="numbers-only"
                                        allowed-tags-pattern="^[0-9]+$">
                            </tags-input>
                            <center>
                                <input class="btn btn-default buttons" type="submit" value="Submit">
                            </center>
                        </div>
                    </form>
                </div>
                <!-- Pin code Tag Input End Here -->
            </div> 
            <!-- End of Panel body -->
        </div>
    </div>
</div>

    <div id="di-msg">

    </div>

</div>