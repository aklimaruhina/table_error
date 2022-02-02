  <?php //if ($orders['orders'] instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
<?php //$per_page=$orders['orders']->perPage(); ?>
<?php //else: ?>
<?php //$per_page=10; ?>
<?php //endif ?>
<?php $page  ='orders';
   $breadcrumbs =[
                 array('url'=>url(''),'name'=>'Billing'),
                 array('url'=>url('admin/orders_list'),'name'=>'Invoice Listing'),
                 array('url'=>url(''),'name'=>'Invoice - Edit'),
   
   ];
// dd($data['order']);
  $total_price = 0; 
  $discount_price = 0.00;
  $discount = 0.00;

  
   ?>
@extends('layouts.admin_layout')
@section('title','Admin | Billing Invoice Edit')
@section('content')
@section('page_header','Billing')
<div class="page-content">
   <div class="row">
      <div class="col-lg-12">
         <h2>Invoice <i class="fa fa-angle-right"></i> Edit</h2>
         <div class="clearfix"></div>
         <div class="col-lg-12">
            <!-- <h2>View All Orders <i class="fa fa-angle-right"></i> Listing</h2> -->
            <div id="success"></div>
            <div class="clearfix"></div>
            @if (session('update_status') === True)
            <div class="alert alert-success alert-dismissable">
               <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
               <i class="fa fa-check-circle"></i> <strong>Success!</strong>
               <p>{{ session('update_message') }}</p>
            </div>
            @endif
            @if (session('update_status') === False )
            <div class="alert alert-danger alert-dismissable">
               <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
               <i class="fa fa-times-circle"></i> <strong>Error!</strong>
               <p>{{ session('update_message') }}</p>
            </div>
            @endif
            @if(isset($search_success))
            <div class="alert alert-success alert-dismissable">
               <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
               <i class="fa fa-check-circle"></i> <strong>Success!</strong>
               <p>{{ $search_success }}</p>
            </div>
            @endif
            @if(isset($search_error))
            <div class="alert alert-danger alert-dismissable">
               <button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>
               <i class="fa fa-times-circle"></i> <strong>Error!</strong>
               <p>{{ $search_error }}</p>
            </div>

            @endif
            <div class="clearfix"></div>
            <div class="pull-left"> Last updated:
               <span class="text-blue">
               {{ date('d M, Y @ g:i A', strtotime($data['lastUpdated'])) }}
               </span>
            </div>
         </div>
         <div class="pull-right">
            <a href="{{url('admin/print_pdf/'.$data['order']->id) }}" class="btn btn-danger" target="_blank">Print &nbsp;<i class="fa fa-print"></i></a>
            <!-- <button class="btn btn-danger" id="print_btn" data-id="{{ $data['order']->id }}">Print &nbsp;<i class="fa fa-print"></i></button>&nbsp; -->
            <a href="{{url('admin/invoice_pdf/'.$data['order']->id) }}" target="_blank" class="btn btn-danger">Download PDF &nbsp;<i class="fa fa-cloud-download"></i></a>
            <a href="#" data-target="#modal-add-email" data-toggle="modal" class="btn btn-danger">Email as PDF &nbsp;<i class="fa fa-envelope"></i></a>&nbsp;
            
            @if ($data['order']->status == 'COMPLETED' && ($data['order']->total_amount >= 0.00) )
            <?php  $status = ''; ?>
            @else
            <?php 
            $status = 'disabled';
            ?>
            @endif
            <a href="#" data-target="#modal-generate-receipt" data-toggle="modal" class="btn btn-danger" {{ $status }}>Generate Receipt &nbsp;<i class="fa fa-file"></i></a>&nbsp;
         
         </div>
         <!--Modal email as pdf start-->
         <div id="modal-add-email" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
            <div class="modal-dialog modal-wide-width">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                     <h4 id="modal-login-label2" class="modal-title">Email as PDF</h4>
                  </div>
                  <div class="modal-body">
                     <div class="form">
                        <div id="error_msg"></div>
                        <form class="form-horizontal" id="send_email">
                           {{csrf_field()}}
                           <div class="form-group">
                              <label class="col-md-3 control-label">Status</label>
                              <div class="col-md-6">
                                 <div data-on="success" data-off="primary" class="make-switch">
                                    <input type="checkbox" checked="checked"/>
                                 </div>
                              </div>
                           </div>
                           <input type="hidden" name="name" value="{{ $data['order']->user->full_name }}">
                           <div class="form-group">
                              <label class="col-md-3 control-label">Name<span class="text-red">*</span></label>
                              <div class="col-md-6">
                                 @if($data['order']->user->full_name)
                                 <input type="text" name="name" class="form-control" value="{{ $data['order']->user->full_name }}">
                                 @else
                                 <input type="text" name="name" class="form-control" value="" placeholder="Enter Your name">
                                 @endif
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-md-3 control-label">Default Email <span class="text-red">*</span></label>
                              <div class="col-md-6">
                                 <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="text" placeholder="Email Address" class="form-control" name="email" value="{{ $data['order']->user->email }}"/>
                                 </div>
                                 note to programmer: default email will be auto displayed here.
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-md-3 control-label">Email CC 1</label>
                              <div class="col-md-6">
                                 <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" placeholder="Email Address" class="form-control" name="emailcc[]"/>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="col-md-3 control-label">Email CC 2</label>
                              <div class="col-md-6">
                                 <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" placeholder="Email Address" class="form-control" name="emailcc[]"/>
                                 </div>
                              </div>
                           </div>
                           <div class="progress email_progress">
                               <div class="progress-bar" role="progressbar" aria-valuenow=""
                               aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                 0%
                               </div>
                           </div>

                           note to programmer: once user clicks "send", there shall be a progress bar to show the emails being sent. Once completed, it shall show "Success" notification otherwise "Error" notification.
                           <div class="form-actions">
                              <div class="col-md-offset-5 col-md-8">
                                 <button class="btn btn-red" type="submit">Send &nbsp; <i class="fa fa-send-o"></i></button> 
                                 <!-- <a href="#" class="btn btn-red">Send &nbsp;<i class="fa fa-send-o"></i></a>&nbsp;  -->
                                 <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> 
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--END MODAL email as pdf-->
         <!--Modal generate receipt start-->
         <div id="modal-generate-receipt" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
            <div class="modal-dialog">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                     <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to generate receipt? </h4>
                  </div>
                  <div class="modal-body">
                     <div class="progress pdf_progress">
                         <div class="progress-bar" role="progressbar" aria-valuenow=""
                         aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                           0%
                         </div>
                     </div>
                     note to programmer: if the selection is "yes", it will show a progress bar that it is converting. Once the conversion is success, shows the "Success" notification and link it to that receipt.
                     <div class="form-actions">
                        <div class="col-md-offset-4 col-md-8"> 
                           <a href="javascript:;" class="btn btn-red progressbtn" data-id="{{ $data['order']->id }}">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!-- modal generate receipt end -->
         <div class="clearfix"></div>
         <p></p>
         <div class="clearfix"></div>
         <ul id="myTab" class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#client-info" data-toggle="tab">Client Information</a></li>
            <li><a href="#invoice-items" data-toggle="tab">Invoice Items</a></li>
         </ul>
         <div id="myTabContent" class="tab-content">
            <div id="client-info" class="tab-pane fade in active">

               {{
               Form::open(
               [
               'route' => [ 'update_order', $data['order']->id ],
               'class' => 'form-horizontal'
               ]
               )
               }}
               <div class="invoice-title">
                  <h2>Invoice</h2>
                  <h3 class="pull-right">
                     Invoice #: MY-{{ $data['order']->transaction_id }}
                     <div class="xs-margin"></div>
                     Receipt #: {{ $data['order']->id }}
                  </h3>
               </div>
               note to programmer: if new invoice is created, pls auto-generate a new invoice # according to the last # sequence and the new inovice status is set to "Unpaid". If this is a "Paid" invoice/order, pls display the "Receipt #".
               <div class="portlet">
                  <div class="portlet-header">
                     <div class="caption">General</div>
                     <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                  </div>
                  <!-- end porlet header -->
                  <div class="portlet-body">
                     <div class="row">
                        <!--  Order Form: Start  -->
                        <div class="form-horizontal">
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Client ID: </label>
                                 <div class="col-md-8">
                                    <p class="form-control-static">
                                       <a href="client_edit.html">

                                       {{ $data['order']->user->user_client_id }}
                                       </a>
                                    </p>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">
                                 Client Name:
                                 </label>
                                 <div class="col-md-8">
                                    <p class="form-control-static">
                                       @if ($data['order']->user->full_name)
                                       <a href="client_edit.html">
                                       {{ $data['order']->user->full_name }}
                                       </a>
                                       @else
                                       <span>
                                       <em>No name provided</em>
                                       </span>
                                       @endif
                                       (<a href="#">View all invoices</a>)
                                    </p>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">
                                 Invoice Date:
                                 </label>
                                 <div class="col-md-8">
                                    <div class="input-group">
                                       {{
                                       Form::text(
                                       'order-invoice-date',
                                       date('m/d/Y', strtotime($data['order']->created_at)),
                                       [
                                       'class'            => 'datepicker-default form-control',
                                       'placeholder'      => 'mm/dd/yyyy',
                                       'data-date-format' => 'mm/dd/yyyy'
                                       ]
                                       )
                                       }}
                                       <div class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                       </div>
                                    </div>
                                    @if ($errors->has('order-invoice-date'))
                                    <p class="text-danger">
                                       {{ $errors->first('order-invoice-date') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Due Date: </label>
                                 <div class="col-md-8">
                                    <div class="input-group">
                                       {{
                                       Form::text(
                                       'order-due-date',
                                       date('m/d/Y', strtotime($data['order']->due_date)),
                                       [
                                       'class'            => 'datepicker-default form-control',
                                       'placeholder'      => 'mm/dd/yyyy',
                                       'data-date-format' => 'mm/dd/yyyy'
                                       ]
                                       )
                                       }}
                                       <div class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                       </div>
                                    </div>
                                    @if ($errors->has('order-due-date'))
                                    <p class="text-danger">
                                       {{ $errors->first('order-due-date') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <h5 class="col-md-4 control-label text-red">
                                    <b>Total:</b>
                                 </h5>
                                 <div class="col-md-8">
                                    <h5 class="form-control-static text-red">
                                       <?php $totalammount = App\Models\Order::updateOrderprice($data['order']->id); ?>
                                       <b>RM {{ number_format($totalammount, 2) }}</b>
                                    </h5>
                                 </div>
                              </div>
                           </div>
                           <!-- end col-md 6 -->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Status: </label>
                                 <div class="col-md-6">
                                    <div class="btn-group">
                                       @if ($data['order']->status === 'COMPLETED')
                                       <button type="button" class="btn btn-success">
                                       Paid
                                       </button>
                                       <button type="button" data-toggle="dropdown" class="btn btn-success dropdown-toggle">
                                       <span class="caret"></span>
                                       <span class="sr-only">
                                       Toggle Dropdown
                                       </span>
                                       </button>
                                       @elseif ($data['order']->status === 'INCOMPLETE')
                                       <button type="button" class="btn btn-warning">
                                       Unpaid
                                       </button>
                                       <button type="button" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">
                                       <span class="caret"></span>
                                       <span class="sr-only">
                                       Toggle Dropdown
                                       </span>
                                       </button>
                                       @else
                                       <button type="button" class="btn btn-danger">
                                       Failed
                                       </button>
                                       <button type="button" data-toggle="dropdown" class="btn btn-danger dropdown-toggle">
                                       <span class="caret"></span>
                                       <span class="sr-only">
                                       Toggle Dropdown
                                       </span>
                                       </button>
                                       @endif
                                       <ul role="menu" class="dropdown-menu">
                                          @if ($data['order']->status !== 'COMPLETED')
                                          <li>
                                             <a
                                                href="{{
                                                route('order_status_update',
                                                [ 'order_id' => $data['order']->id, 'status' => 'COMPLETED' ])
                                                }}"
                                                >
                                             Paid
                                             </a>
                                          </li>
                                          @endif
                                          @if ($data['order']->status !== 'INCOMPLETE')
                                          <li>
                                             <a
                                                href="{{
                                                route('order_status_update',
                                                [ 'order_id' => $data['order']->id, 'status' => 'INCOMPLETE' ])
                                                }}"
                                                >Unpaid</a>
                                          </li>
                                          @endif
                                          @if ($data['order']->status === 'INCOMPLETE' || $data['order']->status === 'COMPLETED')
                                          <li>
                                             <a
                                                href="{{
                                                route('order_status_update',
                                                [ 'order_id' => $data['order']->id, 'status' => 'FAILED' ])
                                                }}"
                                                >
                                             Failed
                                             </a>
                                          </li>
                                          @endif
                                       </ul>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Payment Date: </label>
                                 <div class="col-md-8">
                                    <div class="input-group">
                                       {{
                                       Form::text(
                                       'order-payment-date',
                                       date('m/d/Y', strtotime($data['order']->payment_date)),
                                       [
                                       'class'            => 'datepicker-default form-control',
                                       'placeholder'      => 'mm/dd/yyyy',
                                       'data-date-format' => 'mm/dd/yyyy'
                                       ]
                                       )
                                       }}
                                       <div class="input-group-addon">
                                          <i class="fa fa-calendar"></i>
                                       </div>
                                    </div>
                                    @if ($errors->has('order-payment-date'))
                                    <p class="text-danger">
                                       {{ $errors->first('order-payment-date') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">
                                 Payment Method:
                                 </label>
                                 <div class="col-md-8">
                                    {{
                                    Form::select('order-payment-method',
                                    $data['payment_methods'],
                                    $data['order']->payment_method_id,
                                    [
                                    'class' => 'form-control'
                                    ])
                                    }}
                                    @if ($errors->has('order-payment-method'))
                                    <p class="text-danger">
                                       {{
                                       $errors->first('order-payment-method')
                                       }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Transaction ID: </label>
                                 <div class="col-md-8">
                                    {{
                                    Form::text(
                                    'order-txn-id',
                                    $data['order']->transaction_id,
                                    [ 'class' => 'form-control' ]
                                    )
                                    }}
                                    @if ($errors->has('order-txn-id'))
                                    <p class="text-danger">
                                       {{ $errors->first('order-txn-id') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-md-4 control-label">Cheque #: </label>
                                 <div class="col-md-8">
                                    {{
                                    Form::text(
                                    'order-cheque-num',
                                    $data['order']->cheque_number,
                                    [
                                    'class'       => 'form-control',
                                    'placeholder' => 'eg. PBB304222'
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('order-cheque-num'))
                                    <p class="text-danger">
                                       {{ $errors->first('order-cheque-num') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- end row -->
                  </div>
                  <!-- end porlet-body -->
               </div>
               <!-- end portlet -->
            
               <!-- User Information Portlet: Start -->
               <div class="portlet">
                  <div class="portlet-header">
                     <div class="caption">
                        Client Information
                     </div>
                     <div class="tools">
                        <i class="fa fa-chevron-up"></i>
                     </div>
                  </div>
                  <!-- end porlet header -->
                  <div class="portlet-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="form-horizontal">
                              @if(!$data['order']->id)
                              <div class="form-group">
                                 <label class="col-md-3 control-label">
                                 The Invoice is For <span class="text-red">*</span>
                                 </label>

                                 <div class="col-md-6">
                                    <div class="radio-list">
                                       <label>
                                       {{
                                       Form::radio(
                                       'user-client-process',
                                       'existing-user',
                                       True
                                       )
                                       }}
                                       &nbsp; Existing Client
                                       </label>
                                        <!-- <input id="optionsRadios1" type="radio" name="optionsRadios" value="option1" checked="checked"/>&nbsp; Existing Client</label> -->
                                       <div class="clearfix"></div>
                                       <label>Filter Exisiting Client By</label>
                                       <select name="user-account-type" class="form-control order-client-type invoice-existing-client-field">
                                          @if(isset($data['user_client_accounts'][$data['order']->user->id]['type']) === 'business-account')
                                          <option value="all">All</option>
                                          <option selected="selected" value="business-account">
                                             Business Account
                                          </option>
                                          <option value="individual-account">
                                             Individual Account
                                          </option>
                                          @elseif(isset($data['user_client_accounts'][$data['order']->user->id]['type']) === 'individual-account')
                                          <option value="all">
                                             All
                                          </option>
                                          <option value="business-account">
                                             Business Account
                                          </option>
                                          <option selected="selected" value="individual-account">
                                             Individual Account
                                          </option>
                                          @else
                                          <option value="all">
                                             All
                                          </option>
                                          <option value="business-account">
                                             Business Account
                                          </option>
                                          <option value="individual-account">
                                             Individual Account
                                          </option>
                                          @endif
                                       </select>

                                       <div class="clearfix xs-margin"></div>
                                       <select name="user-client-target" class="form-control user-client-target invoice-existing-client-field">
                                          <option value="_default">
                                             - Please select -
                                          </option>
                                          @if ($data['user_client_accounts'])
                                          @foreach ($data['user_client_accounts'] as $user_id => $user)
                                          @if ($user_id === $data['order']->user->id)
                                          <option value="{{ $user_id }}" data-type="{{ $user['type'] }}" selected="selected">
                                             @else
                                          <option value="{{ $user_id }}" data-type="{{ $user['type'] }}">
                                             @endif
                                             {{ $user['label'] }}
                                          </option>
                                          @endforeach
                                          @endif
                                       </select>
                                       @if($errors->has('user-client-target'))
                                       <p class="text-danger">
                                          {{ $errors->first('user-client-target') }}
                                       </p>
                                       @endif
                                       <div class="clearfix xs-margin"></div>
                                       <label>
                                       {{
                                       Form::radio(
                                       'user-client-process',
                                       'new-user',
                                       False
                                       )
                                       }}
                                       &nbsp; New Client
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              @endif
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Account type <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::select(
                                    'user-client-account-type',
                                    [
                                    '_default' => '- Please select -',
                                    'Business Account' => 'Business Account',
                                    'Individual Account' => 'Individual Account'
                                    ],
                                    $data['order']->user->client->account_type,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-account-type'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-account-type') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <?php  ?>
                              <div class="form-group">

                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 First Name <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-first-name',
                                    $data['order']->user->client->first_name,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-first-name'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-first-name') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Last Name <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-last-name',
                                    $data['order']->user->client->last_name,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-last-name'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-last-name') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Company <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-company',
                                    $data['order']->user->client->company,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-company'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-company') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Email Address <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-email',
                                    $data['order']->user->email,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-email'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-email') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>

                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Phone <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-phone-number',
                                    $data['order']->user->client->phone_number,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-phone-number'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-phone-number') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Mobile Phone <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-mobile-number',
                                    $data['order']->user->client->mobile_number,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-mobile-number'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-mobile-number') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Address <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-address-1',
                                    $data['order']->user->client->address1,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-address-1'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-address-1') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Address 2
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-address-2',
                                    $data['order']->user->client->address2,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-address-2'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-address-2') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Country <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    
                                    {{
                                    Form::select(
                                    'user-client-country',
                                    $data['countries'],
                                    $data['order']->user->client->country_id,
                                    [
                                    'class' => 'form-control user-client-country invoice-new-user-field',
                                    'disabled' => true,
                                    'id' => 'country'
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-country'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-country') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 State <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::select(
                                    'user-client-state',
                                    (session('states')) ? session('states') : [ '_default' => '- Please select -' ],
                                    $data['order']->user->client->state_id,
                                    [
                                    'class' => 'form-control user-client-state invoice-new-user-field',
                                    'disabled' => true,
                                    'id' => 'state'
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-state'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-state') }}
                                    </p>
                                    @endif
                                    <input type="hidden" class="state_id" value="{{$data['order']->user->client->state_id}}">
                                 </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 City <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::select(
                                    'user-client-city',
                                    (session('cities')) ? session('cities') : [ '_default' => '- Please select -' ],
                                    $data['order']->user->client->city_id,
                                    [
                                    'class' => 'form-control user-client-city invoice-new-user-field',
                                    'disabled' => true,
                                    'id' => 'city'
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-city'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-city') }}
                                    </p>
                                    @endif
                                    <input type="hidden" class="city_id" value="{{ $data['order']->user->client->city_id }}">
                                 </div>
                              </div>
                              
                              <div class="clearfix"></div>
                              <div class="form-group">
                                 <label for="inputFirstName" class="col-md-3 control-label">
                                 Postal Code <span class="text-red">*</span>
                                 </label>
                                 <div class="col-md-6">
                                    {{
                                    Form::text(
                                    'user-client-postal-code',
                                    $data['order']->user->client->postal_code,
                                    [
                                    'class' => 'form-control invoice-new-user-field',
                                    'disabled' => true
                                    ]
                                    )
                                    }}
                                    @if ($errors->has('user-client-postal-code'))
                                    <p class="text-danger">
                                       {{ $errors->first('user-client-postal-code') }}
                                    </p>
                                    @endif
                                 </div>
                              </div>
                              <input type="hidden" name="user_id" value="{{ $data['order']->user->id }}">
                              
                              <div class="clearfix"></div>
                           </div>
                        </div>
                        <!-- end col-md-12 -->
                     </div>
                     <!-- end row -->
                     <div class="md-margin"></div>
                  </div>
                  <!-- end porlet-body -->
                  <div class="clearfix"></div>

                  <div class="form-actions">
                     <div class="col-md-offset-5 col-md-7">
                        {{
                        Form::button(
                        "Save &nbsp;<i class='fa fa-floppy-o'></i>",
                        [
                        'type'  => 'submit',
                        'class' => 'btn btn-red'
                        ]
                        )
                        }}&nbsp;
                        <a href="#" data-dismiss="modal" class="btn btn-green">
                        Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i>
                        </a>
                     </div>
                  </div>
               </div>
               {{ Form::close() }}
               <!-- End porlet -->
            </div>

            <!-- end tab client info -->
            <div id="invoice-items" class="tab-pane fade">
               <div class="portlet">
                  <div class="portlet-header">
                     <div class="caption">Invoice Items</div>
                     <p class="margin-top-10px"></p>
                     <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
                     <div class="clearfix"></div>
                     <a href="#" data-target="#modal-add-item" data-toggle="modal" class="btn btn-success">Add New Item &nbsp;<i class="fa fa-plus"></i></a>&nbsp;
                     <div class="btn-group">
                        <button type="button" class="btn btn-primary">Delete</button>
                        <button type="button" data-toggle="dropdown" class="btn btn-red dropdown-toggle"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                        <ul role="menu" class="dropdown-menu">
                           <li>
                              <a href="javascript:;" class="delete_selected_item_link">Delete selected item(s)</a>
                           </li>
                           <li class="divider"></li>
                           <li>
                              <a href="#" data-target="#modal-delete-all" data-toggle="modal">Delete all</a>
                           </li>
                        </ul>
                     </div>
                     <!--Modal add new item start-->
                     <div id="modal-add-item" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog modal-wide-width">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                 <h4 id="modal-login-label3" class="modal-title">Add New Item</h4>
                              </div>
                              <div class="modal-body">
                                 <h5 class="block-heading">Services</h5>
                                 <div id="errorEditBrand"></div>
                                 <form class="form-horizontal" method="post" id="newITems">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                       <div class="form-group" id="custom_plan_category">
                                       <label class="col-md-3 control-label">Select Category</label>
                                       <div class="col-md-6">
                                          
                                          <select class="form-control category_plan" name="custom_plan_category">
                                             <option value="">Select Category</option>
                                             {!! $data['custom_category'] !!}
                                          </select>
                                       </div>
                                    </div>
                                    </div>
                                    <div class="form-group">

                                       <label class="col-md-3 control-label">Service Plan / Service Code <span class="text-red">*</span></label>
                                       <div class="col-md-6">

                                          <select class="form-control service_plan" name="service_plan" id="service_plan">
                                             <option value="">- Please select -</option>
                                             <option value="custom_plan">Custom Plan/Package</option>
                                          </select>
                                          <div class="xs-margin"></div>
                                          <div class="text-blue text-12px">Please select a <b>"Service Plan"</b> &amp; <b>"Service Code"</b> to continue,  eg. for VPS Hosting, <b>Service Plan = "Linux Basic"</b>, <b>Service Code = "VPS58-2-1"</b>.</div>
                                          note to programmer: some of the services does not have a service code, if service plan doesn't have a service code, please leave it blank after the plan name in the above dropdown list.
                                       </div>
                                    </div>
                                    <div class="form-group cstplan" id="custom_plan_data" style="display:none">
                                       <label class="col-md-3 control-label">Custom Plan/Package Name</label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" name="custom_plan_name">
                                       </div>
                                    </div>
                                    <div class="form-group cstplan" id="custom_plan_code" style="display:none">
                                       <label class="col-md-3 control-label">Custom Plan Service Code</label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" name="custom_plan_code">
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Global Discount Name </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="eg. Sample 2" id="discount_name" name="discount_name">
                                          note to programmer: "discount name" and "discount rate" are auto-filled in this section if the selected services in above service dropdown list has applied to the global discount.
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Global Discount Rate </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="Amount" name="discount_amount" id="discount_amount">
                                          <input type="hidden" name="discount_id" value="0" id="discount_id">
                                          <div class="xs-margin"></div>
                                          <select name="discount_rate" class="form-control" id="percentage">
                                             <option value="%">%</option>
                                             <option value="RM">RM</option>
                                          </select>
                                       </div>
                                    </div>
                                    <!-- <div class="form-group">
                                       <label class="col-md-3 control-label">Status</label>
                                       <div class="col-md-6">
                                          <div data-on="success" data-off="primary" class="make-switch">
                                             <input type="checkbox" checked="checked"/>
                                          </div>
                                       </div>
                                    </div> -->
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Promo Code </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="eg. Test123" name="promo_code" id="promo_code">
                                          <input type="hidden" name="promo_id" id="promo_id" value="0">
                                          note to programmer: "promo code" and "discount rate" are auto-filled in this section if the selected services in above service dropdown list has applied to the global discount.
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Discount Rate (Promo Code) </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="Amount" name="promo_amount">
                                          <div class="xs-margin"></div>
                                          <select name="select" class="form-control" id="promo_type">
                                             <option value="P">%</option>
                                             <option value="F">RM</option>
                                          </select>
                                       </div>
                                    </div>
                                    @if(count($data['hosting_plan'])>0)
                                    
                                    
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">SSL Price (RM) </label>
                                       <div class="col-md-6">
                                          <select class="form-control" name="ssl_price" id="ssl_price">
                                             <option value="0" selected>Select Any SSL price</option>
                                             <?php $x=1; ?>
                                             @foreach($data['hosting_plan'] as $i)

                                                <option value="{{$x}}-{{$i->price_annually}}">{{ $x }} Years @  {{$i->price_annually}}/yr</option>
                                             <?php $x++; ?>

                                             @endforeach
                                                            
                                             <!-- <option>- Please select -</option>
                                             <option value="1 year">1 year(s) @ RM239.99/yr</option>
                                             <option value="2 years">2 year(s) @ RM 219.99/yr</option>
                                             <option value="3 years">3 year(s) @ RM 199.99/yr</option> -->
                                          </select>
                                          <div class="xs-margin"></div>
                                          note to programmer: the ssl price dropdown list is dynamic and fectched from the ssl services setup depending on the ssl plan selected above.
                                       </div>
                                    </div>
                                    
                                    @endif
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Unit Price (RM)</label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="0.00" id="unit_price" name="unit_price">
                                          <div class="xs-margin"></div>
                                          <div class="text-blue text-12px">The unit price is for all other packages execpt domain. For single/bulk domain prices, please specify the prices in below <b>"Domain Configuration"</b> section.</div>
                                          note to programmer: auto-fill in the price above after selected a plan. the price will be varied from the selection of the plan above.
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Quantity <span class="text-red">*</span></label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="1" id="quantity" name="quantity" value="1">
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Cycle <span class="text-red">*</span></label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="eg. 1 year(s)" id="cycle" name="cycle" value="1">
                                       </div>
                                    </div>
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Setup Fee (RM) </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="0.00" id="setupfree" name="setupfree" value="0.00">
                                          <div class="xs-margin"></div>
                                          <div class="setuptext"></div>
                                          <div class="text-blue text-12px">If "Setup Fee" is set to <b>RM 0.00</b>, it is <b>"FREE Setup"</b>.</div>
                                       </div>
                                    </div>
                                    <!-- domain configuration start -->
                                    <h5 class="block-heading">Domain Configuration</h5>
                                    <div class="form-group">
                                       <label for="inputFirstName" class="col-md-3 control-label">Domain Name <span class="text-red">*</span></label>
                                       <div class="col-md-6">
                                          <div class="radio-list">
                                             <div class="domain_show">
                                             <label for="rd1">
                                                <input type="radio" name="rd" class="rd rd1" value="1" checked="checked"> Use existing domain, please enter your domain below:
                                                <div class="xs-margin"></div>
                                                 <!--<input type="text" class="form-control" placeholder="eg. webqom.net"> -->
                                              </label>
                                             <label for="rd2">
                                                <input type="radio" name="rd" class="rd rd2" value="2"> Register a new domain, please enter your domain below:
                                                <div class="xs-margin"></div>
                                                <!-- <input type="text" class="form-control"  placeholder="eg. webqom.net"> -->
                                             </label>
                                             </div>
                                             <div class="single_domain">
                                                <label for="rd3">
                                                   <input type="radio" name="rd" class="rd rd3" value="3"> Please specify your domain below (for single domain):
                                                   <div class="xs-margin"></div>
                                                   <!-- <input type="text" class="form-control" placeholder="eg. webqom.net"> -->
                                                </label>
                                             </div>
                                             <!-- <div class="bulk_domain">
                                                <label for="rd4">
                                                   <input type="radio" name="rd" class="rd rd4" value="4"> Please specify your domains below (for bulk domains):
                                                   <div class="xs-margin"></div>
                                                   
                                                </label>
                                             </div> -->
                                          </div>
                                          <div class="form-group">

                                             <input type="text" class="form-control" name="search_domain" id="domain_text" value="" placeholder="eg. yourdomain.com">
                                             <textarea class="form-control" placeholder="Enter up to 20 domain names.Each name must be on a separate line.Examples: yourdomain.com yourdomain.net" name="bulk_domains" rows="6" id="bulk_text" style="display:none" placeholder="eg.Each name must be on a separate line.Examples:yourdomain.com yourdomain.net">
                                          </textarea>
                                          </div>
 
                                          
                                          

                                          <a href="javascript:void(0)" class="btn btn-danger caps chk_btn check_availablity" style="display:none"><i style='display:none;' class="fa fa-lg fa-spinner chk_avl_spnr"></i> <b>Check Availability</b></a>&nbsp;
                                          <a href="javascript:void(0)" class="btn btn-danger caps chk_btn bulk_availability" style="display:none"><i style='display:none;' class="fa fa-lg fa-spinner chk_avl_spnr"></i> <b>Bulk Availability</b></a>&nbsp;
                                          
                                          
                                       </div>
                                    </div>
                                    <div id="domain_status"></div>
                                    <!-- note to programmer: the domain price from 1 year to 10 years is dynamic and fectched from the domain pricing and maybe varied from different TLDs. Same as bulk domain pricing. -->
                                    <div class="single_pricing_table" style="display:none">
                                       <h5 class="block-heading">Single Domain Pricing (RM)</h5>
                                       <!-- <div class="text-blue text-12px">You can specify single domain price in below table for <b>"New Domain Registration"</b>, <b>"Domain Renewal"</b> or <b>"Transfer in a Domain"</b>. If "Domain Price" is set to <b>RM 0.00</b>, it is <b>"FREE Domain"</b>. </div> -->
                                       <div class="xs-margin"></div>
                                       <div class="form-group">
                                          <label for="inputFirstName" class="col-md-3 control-label">Domain Pricing <span class="text-red">*</span></label>
                                          <div class="col-md-6">
                                             <select class="form-control" name="domain_pricing" id="domain_pricing">
                                                <option value="">Select Domain Pricing</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>

                                    <!-- <div class="table-responsive">
                                       <table class="table table-striped table-hover">
                                          <thead>
                                             <th>1 Year(s)</th>
                                             <th>2 Year(s)</th>
                                             <th>3 Year(s)</th>
                                             <th>5 Year(s)</th>
                                             <th>10 Year(s)</th>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                          </tbody>
                                          <tfoot>
                                             <tr>
                                                <td colspan="5"></td>
                                             </tr>
                                          </tfoot>
                                       </table>
                                    </div> -->
                                    <!-- end table responsive -->
                                    <div class="bulk_domain_pricing" style="display:none">
                                       <h6 class="block-heading">Bulk Domain Pricing (RM)</h6>
                                       <div class="text-blue text-12px">You can specify bulk domain price in below table for <b>"Bulk Registration"</b>, <b>"Bulk Renewal"</b> or <b>"Bulk Transfer"</b>.</div>
                                       <div class="xs-margin"></div>
                                       <div class="table-responsive">
                                          <!-- <table class="table table-striped table-hover">
                                             <thead>
                                                <th>Domains</th>
                                                <th>1 Year(s)</th>
                                                <th>2 Year(s)</th>
                                                <th>3 Year(s)</th>
                                                <th>5 Year(s)</th>
                                                <th>10 Year(s)</th>
                                             </thead>
                                             <tbody>
                                                <tr>
                                                   <td>1-5</td>
                                                   <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                   <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                   <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                   <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                             <tr>
                                                <td>6-20</td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                             <tr>
                                                <td>21-49</td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                             <tr>
                                                <td>50-100</td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                             <tr>
                                                <td>101-200</td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                             <tr>
                                                <td>201-500</td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                                <td><input type="text" class="form-control" placeholder="0.00"></td>
                                             </tr>
                                          </tbody>
                                          <tfoot>
                                             <tr>
                                                <td colspan="6"></td>
                                             </tr>
                                          </tfoot>
                                       </table> -->
                                       </div>
                                    </div>
                                    <!-- end table responsive -->
                                    <!-- <div class="form-group">
                                       <label class="col-md-3 control-label">Discount </label>
                                       <div class="col-md-6">
                                          <input type="text" class="form-control" placeholder="Amount">
                                          <div class="xs-margin"></div>
                                          <select name="select" class="form-control">
                                             <option value="%">%</option>
                                             <option value="RM">RM</option>
                                          </select>
                                       </div>
                                    </div> -->
                                    <div class="form-group">
                                       <label class="col-md-3 control-label">Domain Addons </label>
                                       <div class="col-md-6">
                                          <div class="checkbox-list margin-top-10px">
                                             @foreach($data['domain_pricings'] as $dprice)
                                             <label for="{{ $dprice->id}}">
                                             {{
                                                         Form::checkbox(
                                                            'addons[]',
                                                            $dprice->id
                                                         )
                                             }}
                                             {{ $dprice->title }}(@ RM {{ $dprice->price }} /yr)
                                             </label>
                                             @endforeach
                                          </div>
                                       </div>
                                    </div>
                                    <div id="custom_plan_box" style="display:none">
                                       <div id="inputFormRow">
                                          <div class="form-group">
                                             <label class="col-md-3 control-label">Custom Plan Specification </label>
                                             <div class="col-md-6">
                                                <input type="text" name="custom_title[]" class="form-control" placeholder="Enter title" autocomplete="off"> <br/>
                                                <input type="text" name="custom_description[]" class="form-control" placeholder="Enter Details" autocomplete="off"><br/>
                                                <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
                                             </div>
                                          </div>
                                       </div>
                                       <div id="newRow"></div>
                                       <div class="form-group">
                                          <div class="col-md-6 col-md-offset-3">
                                             <button id="addRow" type="button" class="btn btn-info">Add More</button>  
                                          </div>
                                       </div>
                                    </div>
                                    
                                    <div class="form-actions">
                                       <div class="col-md-offset-5 col-md-8"> <button  class="btn btn-red">Save &nbsp;<i class="fa fa-floppy-o"></i></button>&nbsp; <button href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></button> </div>
                                    </div>
                                 </form>
                              </div>
                              <!-- end modal body -->
                           </div>
                        </div>
                     </div>
                     <!--END MODAL add new item -->
                     <!--Modal delete selected items start-->
                     <div id="modal-delete-selected" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                 <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete the selected item(s)? </h4>
                              </div>
                              <div class="modal-body">
                                 <div class="selected_client_list"></div>

                                 <!-- <p><strong>Service Code:</strong> DN<br/>
                                    <strong>Domain Registration:</strong> webqom.net 
                                 </p> -->
                                 <div class="form-actions">
                                    <div class="col-md-offset-4 col-md-8"> 
                                       <a href="#" class="btn btn-red delete_selected">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!--Modal delete selected items start-->
                     <div id="modal-delete-selectedd" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" data-dismiss="modal" aria-hidden="true" class="close">×</button>
                                 <h4 id="modal-login-label3" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete selected items? </h4>
                              </div>
                              <div class="modal-body">
                                 <div class="form-actions">
                                    <div class="alert alert-danger">
                                       Please select at least  one order for delete
                                    </div>
                                    <div class="col-md-offset-4 col-md-4">
                                       <a href="#" data-dismiss="modal" class="btn btn-info btn-block">
                                       Ok
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- modal delete selected items end -->
                     <!--Modal delete all items start-->
                     <div id="modal-delete-all" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                        <div class="modal-dialog">
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                 <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete all items? </h4>
                              </div>
                              <div class="modal-body">
                                 <div class="form-actions">
                                  {{ Form::open([ 'url' => url('admin/orders/deleteAllItem') ]) }}
                                    <div class="col-md-offset-4 col-md-8">
                                      <a href="#" class="btn btn-red delete-all-btn">
                                        Yes &nbsp;<i class="fa fa-check"></i>
                                      </a>&nbsp;
                                      <a href="#" data-dismiss="modal" class="btn btn-green">
                                        No &nbsp;<i class="fa fa-times-circle"></i>
                                      </a>
                                       <input type="hidden" name="items_order_id" value="{{ $data['order']->id }}">
                                      {{ Form::submit('delete all', ['class' => 'delete-all-submit hidden']) }}
                                    </div>
                                  {{ Form::close() }}
                                </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- modal delete all items end -->
                  </div>
                  <!-- end porlet header  -->
                  <div class="portlet-body">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="table-responsive">
                              {!! Form::open(['url' => url('admin/orders/deleteSelectedItem')]) !!}

                              <table class="table table-checkout table-striped">
                                 <thead>
                                    <tr>
                                       <th width="1%"><input id="master" type="checkbox"/></th>
                                       <th>#</th>
                                       <th>Services</th>
                                       <th class="text-center">Cycle</th>
                                       <th class="text-center">Qty</th>
                                       <th class="text-center">Global Discount Name <br/> / Global Discount Rate</th>
                                       <th class="text-center">Promo Code <br/> / Discount Rate</th>
                                       <th class="text-right">Price / SSL Price</th>
                                       <th class="text-center">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php $main_price = 0;$domain_price = 0; ?>
                                    <?php 

                                     ?>
                                    @forelse($data['order']->orderItems as $k=>$v)
                                    <?php $plan_details = App\Models\Plan::get_plan_details($v['plan_id']);                                     
                                    ?>
                                    <?php $plan_details = json_decode(json_encode($plan_details)); ?>
                                   <?php  ?>
                                   @if(!empty($plan_details))
                                    <?php
                                        $main_price = number_format((float)$plan_details->price_annually+$plan_details->setup_fee_one_time, 2, '.', '');
                                        $domain_price = number_format((float)$v['price'] - ($plan_details->price_annually+$plan_details->setup_fee_one_time), 2, '.', ''); ?>
                                    @else

                                    @php $main_price = $v->price; $domain_price = 0;@endphp
                                    @endif
                                    <?php

                                      if($v->price != ''){
                                      $row_price = $v->price;
                                    }else{
                                      $row_price = 0.00;
                                    } 
                                    ?>
                                    @if($v->type == 2)
                                    <?php $text = 'Transfer'; ?>
                                    @else
                                    <?php $text = 'Registration'; ?>
                                    @endif
                                       <tr id="orderItem_{{$v->id}}">
                                          <td><input class="sub_chk" entity="{{$v->id}}" name="items_checkbox[]" value="{{ $v->id }}" type="checkbox"/></td>
                                          <td>{{$k+1}}</td>
                                          <td>
                                             <table border="5px" bordercolor="#F35557">
                                            @if(!empty($plan_details))

                                       <tr><td>
                                       @foreach($data['plans'] as $plan)

                                       @if($v['plan_id'] === $plan->id)
                                       <?php $discount = App\Models\Promotion::get_discount((int)$v['plan_id']);
                                       if($discount != NULL){
                                         $discount = json_decode(json_encode($discount));
                                        
                                        if($discount->discount_by == 'amount'){
                                          $discount = $discount->discount;
                                        }else{
                                          $discount = ( $v['price'] * $discount->discount / 100);
                                        }
                                       }else{
                                        $discount = 0.00;
                                       } 
                                        ?>
                                             <b>Service Code: </b> <span class="sitecolor">{{!empty($plan->service_code) ? $plan->service_code : 'DN'}}</span><br/>
                                             <b>Hosting Plan:</b> <span class="sitecolor caps">{{!empty($plan->plan_name) ? $plan->plan_name : ''}}</span><br/>
                                          
                                        @endif

                                        @endforeach
                                      <b>Server Specification:</b>
                                      <ul>
                                          @php
                                             $featured_plans = App\Models\PlanFeature::where('page', $plan_details->page)->where('status', 1)->get();
                                          @endphp
                                          @if(!empty($featured_plans) && count($featured_plans)>0)
                                            @foreach($featured_plans as $i)
                                              @php
                                                $details = App\Models\PlanFeatureDetail::where('plan_feature_id', $i->id)->where('plan_id', $v->plan_id)->first();
                                              @endphp
                                              @if ($details)
                                              <li><i class="fa icon-arrow-right"></i>&nbsp;&nbsp;{{$i->title}}:
                                                <span data-sel="{{$i->title}}">{{ $details->description }}</span>
                                              </li>
                                              @endif
                                            @endforeach
                                          @endif
                                      </ul>
                                        @else
                                        <?php $discount = 0.00; ?>
                                     </td>
                                  </tr>
                                      @endif
                                      <?php if(isset($v->addons) && $v->addons != "" && $v->addons != null){
                                          $addons_vl = explode(',', $v->addons); ?>
                                          
                                       <tr>
                                          <td>
                                      <b>Domain Addons:</b>
                                      <ul>
                                          
                                      
                                      @foreach($addons_vl as $addon)
                                      @foreach($data['domain_pricings'] as $dprice)
                                      <?php 
                                      if($addon == $dprice->id){ 
                                          $row_price += $dprice->price;
                                      ?>
                                      <li><i class="fa icon-arrow-right"></i>{{$dprice->title}} (RM {{ number_format($dprice->price, 2) }})</li>
                                     <?php }
                                      ?>
                                        
                                      @endforeach
                                      @endforeach
                                      </ul>
                                      </td>
                                      </tr>
                                      <?php } ?>
                                      <?php if(!empty($v->ssl_price) && $v->ssl_price != '0.00'){ ?>
                                          <?php $ssl_vl = explode('-', $v->ssl_price); ?>
                                          <tr><td>
                                          <b>SSL Price: </b> RM {{ $ssl_vl[1] }}
                                       </td>
                                    </tr>
                                          <?php } ?>
                                          <tr><td>
                                    <b>Domain <?php echo $text; ?>:</b> <span class="sitecolor">{{$v['services']}}</span><br/>
                                 </td>
                              </tr>
                                             <!-- <ul class="list-style">
                                                <li><i class="fa icon-angle-right"></i> DNS Management</li>
                                                <li><i class="fa icon-angle-right"></i> Email Forwarding</li>
                                                <li><i class="fa icon-angle-right"></i> ID Protection</li>
                                             </ul> -->
                                             </table>
                                          </td>
                                          <td class="text-center">
                                             
                                             <div class="pull-left">
                                                <table border="5px" bordercolor="#F35557">
                                              @if(!empty($plan_details))
                                              <tr><td>
                                              <?php 

                                              if($main_price != $v['price']){  ?>
                                              {{$v['cycle']}} <?php echo "years"; ?> <br/>
                                                  <?php echo '1 years'; ?>
                                                
                                              <?php }else{ ?>
                                                {{$v['cycle']}} <?php if($v['cycle'] == 1) echo "year"; else echo "years"; ?> <br/>
                                              <?php } ?>
                                              @else
                                              @if(!empty($v['cycle']))
                                              <?php $cycle = $v['cycle']; ?>
                                              @else
                                              <?php $cycle = 1; ?>
                                              @endif
                                              {{ $cycle }} <?php if($cycle == 1) echo "year"; else echo "years"; ?> <br/>
                                              </td></tr>
                                              @endif
                                              <?php if(!empty($v->ssl_price) && $v->ssl_price != '0.00'){ ?>
                                             <tr><td>
                                          <?php $ssl_vl = explode('-', $v->ssl_price); echo '<br>';?>

                                           {{ $ssl_vl[0] }} Years
                                        </td>
                                        </tr>
                                          <?php } ?>
                                       </table>
                                            </div>
                                             
                                          </td>
                                          <td class="text-center">
                                             @if(!empty($plan_details))
                                                @if($main_price != $v['price']) 
                                                   {{$v['qty']}} <br/> {{$v['qty']}}
                                                @else
                                                  {{$v['qty']}} <br/>
                                                @endif
                                             @else
                                               {{$v['qty']}} <br/>
                                             @endif
                                             <div>   
                                            
                                             <?php if(isset($v['addons']) && $v['addons'] != "" && $v['addons'] != null){
                                                $addons_vl = explode(',', $v['addons']); ?>
                        
                                             <ul> 
                                            @foreach($addons_vl as $addon)
                                            @foreach($data['domain_pricings'] as $dprice)
                                            
                                            <?php if($addon == $dprice->id){ ?>
                                              <li>1</li>
                                            <?php } ?>
                                              
                                            @endforeach
                                            @endforeach
                                            </ul>

                                            <?php } ?>
                                         </div>
                                   </td>
                                          <td class="text-center">{{ $discount }}</td>
                                          <td class="text-center">0.00</td>
                                          <td class="text-right"> 
                                          @if(!empty($plan_details))
                                      <?php 
                                        $domain_price = number_format((float)$v['price'] - ($plan_details->price_annually+$plan_details->setup_fee_one_time), 2, '.', '');
                                        if($main_price != $v['price']){ ?>
                                          RM {{ $domain_price }} <br>
                                          RM {{ $main_price }}
                                 
                                        <?php }else{
                                       ?>
                                          RM {{ number_format(($v['price']?$v['price']:'0'),2)}} <br>

                                       <?php } ?>
                                     
                                      @else
                                       
                                       RM {{ number_format(($v['price']?$v['price']:'0'),2)}}

                                      @endif
                                     
                                      <br>



                                        <div>  
                                            <?php if(isset($v['addons']) && $v['addons'] != "" && $v['addons'] != null){
                                                $addons_vl = explode(',', $v['addons']); ?>
                        
                                            <ul> 
                                            @foreach($addons_vl as $addon)
                                            @foreach($data['domain_pricings'] as $dprice)

                                            <?php if($addon == $dprice->id){ ?>
                                              <li>RM {{ number_format($dprice->price, 2)}}</li>
                                            <?php } ?>
                                              
                                            @endforeach
                                            @endforeach
                                            </ul>

                                            <?php } ?>
                                         </div>
                                         <div>
                                          <?php if(!empty($v->ssl_price) && $v->ssl_price != '0.00'){ ?>
                                             <?php $ssl_vl = explode('-', $v->ssl_price); ?>
                                              RM {{ $ssl_vl[1] }}

                                          <?php
                                          $row_price += $ssl_vl[1];
                                           } ?>
                                         </div>
                                         <?php if(!empty($v->setup_fee) && $v->setup_fee != '0.00'){
                                          echo "RM ".$v->setup_fee; 
                                          $row_price += $v->setup_fee;  ?>
                                         <?php } ?>


                                          </td>
                                          <td class="text-center">
                                             <a href="{{ route('editItems', $v->id) }}" data-hover="tooltip" data-placement="top" title="Edit" data="{{json_encode($v, true)}}" class="edit_items"><span class="label label-sm label-success"><i class="fa fa-pencil"></i></span></a> 
                                             <a href="#" class="delete_icon" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-1" data-id="{{$v->id}}" data-regname="{{ $v->services }}" data-toggle="modal"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>                                       
                                          </td>                                       
                                       </tr>
                                       <?php $total_price += $row_price; 
                                        $discount_price += $discount; 
                                      ?>    
                                    @empty
                                    @endforelse
                                    <?php
                                    $grand_total = $total_price - $discount_price;  
                                    ?>                             
                                    <tr>
                                       <td class="thick-line" colspan="5"></td>
                                       <td class="thick-line text-right">
                                          <h6><b>Subtotal:</b></h6>
                                       </td>
                                       <td class="thick-line text-right" colspan="2">
                                          <h6><b>RM {{number_format($total_price, 2)}}</b></h6>
                                       </td>
                                       <td class="thick-line text-right"></td>
                                    </tr>
                                    <tr>
                                       <td class="no-line" colspan="5"></td>
                                       <td class="no-line text-right">
                                          <h6 class="text-red"><b>Discount:</b></h6>
                                       </td>
                                       <td class="no-line text-right" colspan="2">
                                          <h6 class="text-red"><b>- RM {{number_format($discount_price, 2)}}</b></h6>
                                       </td>
                                       <td class="no-line text-right"></td>
                                    </tr>
                                    <tr>
                                       <td class="no-line" colspan="5"></td>
                                       <td class="no-line text-right">
                                          <h6><b>6% GST:</b></h6>
                                       </td>
                                       <td class="no-line text-right" colspan="2">
                                          <h6><b>RM 0.00</b></h6>
                                       </td>
                                       <td class="no-line text-right"></td>
                                    </tr>
                                    <tr>
                                       <td class="no-line" colspan="5"></td>
                                       <td class="thick-line text-right">
                                          <h5 class="text-red"><b>Total:</b></h5>
                                       </td>
                                       <td class="thick-line text-right" colspan="2">
                                          <h5 class="text-red"><b>RM {{number_format($grand_total, 2)}}</b></h5>
                                       </td>
                                       <td class="thick-line text-right"></td>
                                    </tr> 
                                    <input type="hidden" name="grand_total" class="grand_total" value="{{ $grand_total }}">
                                    <input type="hidden" name="item_order_id" value="{{ $data['order']->id }}">
                                    {{ Form::submit('Submit', [ 'class' => 'delete-submit hidden' ]) }}

                                 </tbody>
                              </table>
                              {{ Form::close() }}
                           </div>
                           <!-- end table responsive -->
                           <div class="clearfix"></div>
                        </div>
                        <!-- end col-md-12 -->
                     </div>
                     <!-- end row -->
                     <div class="clearfix"></div>
                  </div>
                  <!-- end portlet-body -->
                   {{
                     Form::open(
                     [
                     'route' => [ 'update_invoice_order', $data['order']->id ],
                     'class' => 'form-horizontal'
                     ]
                     )
                     }}
                     <input type="hidden" name="total_number" value="{{ $grand_total }}">

                  <div class="form-actions">
                     <div class="col-md-offset-5 col-md-7">
  
                        <button type="submit" class="btn btn-red save_items">Save &nbsp;<i class="fa fa-floppy-o"></i></button>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">Cancel &nbsp;<i class="glyphicon glyphicon-ban-circle"></i></a> </div>
                  </div>
                  {{ Form::close() }}
               </div>
               <!-- end portlet -->
            </div>
            <!-- end tab item details -->
         </div>
         <!-- end tab content -->
      </div>
      <!-- end col-lg-12 -->
      <div class="col-lg-12">
         <div class="portlet portlet-blue">
            <div class="portlet-header">
               <div class="caption text-white">Transactions</div>
               <div class="tools"> <i class="fa fa-chevron-up"></i> </div>
            </div>
            <div class="portlet-body">
               <div class="table-responsive mtl">
                  <table id="example1" class="table table-hover table-striped">
                     <thead>
                        <tr>
                           <th width="1%"><input type="checkbox"/></th>
                           <th>#</th>
                           <th><a href="#sort by transaction id">Transaction ID</a></th>
                           <th><a href="#sort by payment date">Payment Date</a></th>
                           <th><a href="#sort by payment method">Payment Method</a></th>
                           <th><a href="#sort by amount">Amount</a></th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><input type="checkbox"/></td>
                           <td>1</td>
                           <td>{{ $data['order']->transaction_id }}</td>
                           <td>{{ date('jS M Y', strtotime($data['order']->payment_date))}}</td>
                           <td>
                              @if($data['order']->payment_method)
                              {{ $data['order']->payment_method->name }}
                              @else
                              {{ "Not Specified" }}
                              @endif
                           </td>
                           <td>
                              <?php $totalammount = App\Models\Order::updateOrderprice($data['order']->id); ?>
                                       <b>RM {{ number_format($totalammount, 2) }}</b></td>
                           <td>
                              <a href="#" data-hover="tooltip" data-placement="top" title="Delete" data-target="#modal-delete-transaction" data-toggle="modal"><span class="label label-sm label-red"><i class="fa fa-trash-o"></i></span></a>
                              <!--Modal delete start-->
                              <div id="modal-delete-transaction" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
                                          <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this transaction? </h4>
                                       </div>
                                       <div class="modal-body">
                                          <p>
                                             <strong>Transaction ID:</strong> {{ $data['order']->transaction_id }} <br/>
                                             <strong>Payment Method:</strong>
                                             @if($data['order']->payment_method)
                                             {{ $data['order']->payment_method->name }}
                                             @else
                                             {{"Not Specified"}}
                                             @endif
                                          </p>
                                          <div class="form-actions">
                                             <div class="col-md-offset-4 col-md-8"> <a href="#" class="btn btn-red">Yes &nbsp;<i class="fa fa-check"></i></a>&nbsp; <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <!-- modal delete end -->
                           </td>
                        </tr>
                     </tbody>
                     <tfoot>
                        <tr>
                           <td colspan="4"></td>
                           <td class="text-left">
                              <h5 class="text-red"><b>Balance:</b></h5>
                           </td>
                           <td class="text-left">
                              <h5 class="text-red">
                                 <?php $totalammount = App\Models\Order::updateOrderprice($data['order']->id); ?>
                                       <b>RM {{ number_format($totalammount, 2) }}</b></h5>
                           </td>
                           <td></td>
                        </tr>
                     </tfoot>
                  </table>
                  <div class="clearfix"></div>
               </div>
               <!-- end table responsive -->
            </div>
            <!-- end portlet body -->
         </div>
         <!-- end portlet -->
      </div>
      <!-- end col-lg-12 -->
   </div>
   <!-- end row -->
</div>
<!-- InstanceEndEditable -->
<!--END CONTENT-->

  <!--Modal delete start-->
  <div id="modal-delete-1" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true" class="modal fade">
      <div class="modal-dialog">
         
         <div class="modal-content">
            
            <div class="modal-header">
               <button type="button" data-dismiss="modal" aria-hidden="true" class="close">&times;</button>
               <h4 id="modal-login-label4" class="modal-title"><a href=""><i class="fa fa-exclamation-triangle"></i></a> Are you sure you want to delete this item? </h4>
            </div>
            <div class="modal-body">
               <form method="POST" action="{{ url('admin/invoice_item_delete') }}">
                  <p><strong>Service Code:</strong> DN <br/>
                  <strong>Domain Registration:</strong> <span id="registration_name"></span>
                  </p>
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
                   <input type="hidden" id="item_id" name="item_id">
                   <input type="hidden" id="order_id" name="order_id" value="{{ $data['order']->id }}">
                   <input type="hidden" name="_method" value="DELETE">
                   <input type="hidden" name="selected-item" id="selected-item">
                   <div class="row">
                     <div class="col-md-offset-4 col-md-8">  
                        <button type="submit" class="btn btn-red delete_btn">Yes &nbsp;<i class="fa fa-check"></i></button>&nbsp; 
                        <a href="#" data-dismiss="modal" class="btn btn-green">No &nbsp;<i class="fa fa-times-circle"></i></a> 
                      </div>
                   </div>
                      
               </form>
            </div>
         </div>
            
      </div>
   </div>
   <!-- modal delete end -->

@section('custom_scripts')
<script src="{{url('').'/resources/assets/admin/'}}js/tableHTMLExport.js"></script>
{{-- <script src="/js/tableHTMLExport.js"></script> --}}<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.10/jspdf.plugin.autotable.min.js"></script>
<script src="{{url('').'/resources/assets/admin/'}}js/jQuery.print.js"></script>
<script type="text/javascript" src="https://malsup.github.io/jquery.form.js"></script>
<script>
   $(document).ready(function() {
      $('.hosting').hide();
      $('.cstplan').hide();
      load_countries();

      $("#addRow").click(function () {
            var html = '';
            html += '<div id="inputFormRow">';
            html += '<div class="form-group">';
            html += '<label class="col-md-3 control-label">Custom Plan Specification </label>';
            html += '<div class="col-md-6">';
            html += '<input type="text" name="custom_title[]" class="form-control" placeholder="Enter title" autocomplete="off"> <br/>';
            html += '<input type="text" name="custom_description[]" class="form-control" placeholder="Enter Details" autocomplete="off"><br/>';
            html += '<button id="removeRow" type="button" class="btn btn-danger">Remove</button>';
            html += '</div>';
            html += '</div>';
            html += '</div>';

            $('#newRow').append(html);
        });
       $(document).on('click', '#removeRow', function () {
           $(this).closest('#inputFormRow').remove();
       });

       $('#master').on('click', function(e) {
           console.log("$(this).is(':checked',true)");
           console.log($(this).is(':checked',true));
           if($(this).is(':checked',true))
           {
               $(".sub_chk").prop('checked', true);
           } else {
               $(".sub_chk").prop('checked',false);
           }
       });
       // $('.category_plan').on('change', function(e){
       //   $('.hosting').hide();

       //   var val = $(this).find(':selected').attr('data-slug')
       //   if(val != ''){
       //      $('.'+val).show();
       //      $('.'+val).find('textarea').attr ('name', 'description');
       //   }

       // })
       $("#service_plan").on('change', function(e){
         var val = $(this).val();
         if(val == 'custom_plan'){
            $('#custom_plan_data').show();
            $('#custom_plan_category').show();
            $('#custom_plan_code').show();
            $('#custom_plan_box').show();
         }else{
            $('.cstplan').hide();
            $('#custom_plan_data').hide();
            $('#custom_plan_code').hide();
            $('#custom_plan_box').hide();
         }
         $.ajax({
           url: '/admin/get_plan_detail/' + val,
           type: 'GET'
         })
         .done(function(response) {
            const obj = JSON.parse(response);
            console.log(obj.status);
            if(obj.status === 1 ){
               if(obj.plans.setup_fee_one_time == '0' || obj.plans.price_type=="Free"){

                  text = '<i class="fa fa-check sitecolor"></i>'; 
                  $('#setuptext').html('Setup Free');
                  $('#setupfree').hide();
               }else{
                  $('#setupfree').val(obj.plans.setup_fee_one_time);
               }
               if(obj.plans.price_annually=='0' || obj.plans.price_annually=='' || obj.plans.price_type=="Free")
               {
                  $('#unit_price').val('0.00');
               }
               else{
                  $('#cycle').val(1);
                  $("#quantity").val(1);
                  $('#unit_price').val(obj.plans.price_annually);
               }
         
            }else{

            }
         });

       })
       // $('.delete_selected_item_link').on('click', function () {
       //     checked_orders = $(".sub_chk:checked");
           
       //     if (checked_orders.length > 0) {
       //         var html = "";
       //         checked_orders.each(function() {
       //             reference_id = $(this).val();
       //             console.log(reference_id);
       //             html += "<p>";
       //             html += "<strong>#" + $('.order-index-' + reference_id).text() + ":</strong> ";
       //             html += $('.order-txn-id-' + reference_id).text() + " - ";
       //             html += $('.order-client-name-' + reference_id).text();
       //         });
       //         $(".selected_client_list").html(html);
       //         $("#modal-delete-selected").modal('show');
       //     } else {
       //         $('#modal-delete-unselect').modal('show');
       //     }
       // });
   
     if ($('input[name="user-client-process"]:checked').val() === 'new-user') {
       $('.invoice-existing-client-field').prop('disabled', true);
       $('.invoice-new-user-field').prop('disabled', false);
     }
     $('.invoice-new-user-field').prop('disabled', false);
     $('.user-client-country').on('change', function (event) {
       var country_id = event.target.value;
   
       $('.user-client-state option:gt(0)').remove();
   
       if (country_id !== '_default') {
         $.ajax({
           url: '/get_state/' + country_id,
           type: 'GET'
         })
         .done(function(response) {
           var state_dropdown = $('.user-client-state');
   
           response.forEach(function (element) {
             var new_state = $('<option></option>').attr('value', element.id)
                                               .text(element.name);
             state_dropdown.append(new_state);
           });
         });
       }
     });
   
     $('.user-client-state').on('change', function (event) {
       var state_id = event.target.value;
   
       $('.user-client-city option:gt(0)').remove();
   
       if (state_id !== '_default') {
         $.ajax({
           url: '/get_city/' + state_id,
           type: 'GET'
         })
         .done(function(response) {
           var state_dropdown = $('.user-client-city');
   
           response.forEach(function (element) {
             var new_cities = $('<option></option>').attr('value', element.id)
                                               .text(element.name);
             state_dropdown.append(new_cities);
           });
         });
       }
     });
   $('.category_plan').on('change', function(event){
      var cat_id = event.target.value;
      $('.cstplan').hide();
      $.ajax({
          url: '{{ url("/admin/invoice/categoryProducts") }}',
          type: 'POST',
          dataType: 'json',
          data: '_token=<?php echo csrf_token() ?>&category_id=' + cat_id,
          beforeSend: function () {

          },
          complete: function () {

          },
          success: function (response) {
            var html = '';
            var categoryplan = $('.service_plan');
            console.log(response);
            console.log(response['discount']);

            if(response['discount']){
            const obj = response['discount'];   
               $('#discount_amount').val(obj.discount);
               $('#discount_name').val(obj.discount_name);
                 if(obj.discount_by === 'amount'){
                    $('#percentage option[value="RM"]').attr("selected", "selected");

                 }else{
                     $('#percentage option[value="%"]').attr("selected", "selected");

                 }

               $('#discount_id').val(obj.id);
            }
            if(response['promocode']){
               // const promo = JSON.parse(response['promocode']);
               const promo = response['promocode'];
               $('#promo_code').val(promo.promo_code);
                 if(promo.discount_type === 'P'){
                     $('#promo_type option[value="%"]').attr("selected", "selected");
                 }else{
                       
                        $('#promo_type option[value="RM"]').attr("selected", "selected");
                 }
               $('#promo_id').val(obj.discount);
            }
            categoryplan.empty().prepend('<option value="">- Please select -</option><option value="custom_plan">Custom Plan/Package</option>');
              if (response['products']) {
                  for (var i = 0; i < response['products'].length; i++) {
                      elm = response['products'][i];
                      console.log(elm);
                      var option = $('<option></option>').attr('value', elm.id)
                                               .text(elm.plan_name);
                         
                     categoryplan.append(option);
                  }
                  
              }
          }
      });
   })
     $('.order-client-type').on('change', function (event) {
       var account_type = event.target.value;
   
       $('.user-client-target option:gt(0)').remove();
   
       $.ajax({
         url: '/admin/users?account_type=' + account_type,
         type: 'GET'
       })
       .done(function(response) {
         var users_dropdown = $('.user-client-target');
   
         var users_key = Object.keys(response);
         users_key.forEach(function (element) {
           var new_users = $('<option></option>').attr('value', element)
                                             .attr('data-type', response[element].type)
                                             .text(response[element].label);
           users_dropdown.append(new_users);
         });
       });
     });
     $('.disc_name').on('change', function (event) {
       var discount_id = event.target.value;
   
       if (discount_id !== -1) {
         $.ajax({
           url: '/admin/get_discount/' + discount_id,
           type: 'GET'
         })
         .done(function(response) {
            const obj = JSON.parse(response);

           $('#discount_amount').val(obj.discount);
           if(obj.discount_by === 'amount'){
              $('#percentage option[value="RM"]').attr("selected", "selected");

           }else{
               $('#percentage option[value="%"]').attr("selected", "selected");

           }
         });
       }
     });
   
     /**
      * User Account type events: Change target client user
      */
     $('input[name="user-client-process"]').on('click', function() {
       value = $('input[name="user-client-process"]:checked').val();
   
       if (value === 'existing-user') {
         $('.invoice-existing-client-field').prop('disabled', false);
         $('.invoice-new-user-field').prop('disabled', true);
       } else {
         $('.invoice-existing-client-field').prop('disabled', true);
         $('.invoice-new-user-field').prop('disabled', false);
       }
     });
     $('.delete-all-btn').on('click', function (event) {
      event.preventDefault();
      $('.delete-all-submit').click();
    })
   
           $('.delete_selected').one('click', function(e) {
   
               $('.delete-submit').click();
             });
   
   
           $('.delete_selected_item_link').on('click', function(e) {
               console.log('clicked success');
               var allVals = [];
               $(".sub_chk:checked").each(function() {
                   allVals.push($(this).val());
               });
   
               if(allVals.length <=0)
               {
                   $('#modal-delete-selectedd').modal('show');
   
                   return false;
                   //$.toaster({ priority :'danger', title : 'Post', message : 'Please select a row'});
               }else{
                 var html = "";
                 //"<p><strong>#1:</strong> Hock Lim - hock@webqom.com</p>";
   
                 $(".sub_chk:checked").each(function() {
                     html += "<p><strong>#"+$(this).closest('tr').children('td:eq(1)').text()+":</strong> "+$(this).closest('tr').children('td:eq(2)').text()+" - Price"+$(this).closest('tr').children('td:eq(7)').text()+"</p>";
                 });
                 $(".selected_client_list").html(html);
                 $("#modal-delete-selected").modal('show');
               }
   
   
             });
   
     $('#master').on('click', function(e) {
        if($(this).is(':checked',true))
        {
           $(".sub_chk").prop('checked', true);
        } else {
           $(".sub_chk").prop('checked',false);
        }
       });
   
   
     $(".delete_icon").click(function(e){
         var id = $(this).data('id');
         var name = $(this).data('regname');
         $('#registration_name').text(name);
         $('#item_id').val(id);
         $("#grand_val").val(grand_val);
         $("#modal-delete-1").modal('show');
     });
     $('.delete_btn').on('submit', function (){
      
        var token   = $('meta[name="csrf-token"]').attr('content');
        var id = $('#item_id').val();
        $.ajax({
            url: base_url+'/admin/invoice_item_delete',
            type: 'POST',
            data: {
            _token: token,
            id : id,
            },
            success: function(response) {
               console.log(response);
               if(response){
                  $("#orderItem_"+id).remove();
               }
               window.location.reload = "{{ url('admin/billing_invoice_edit/')}}" + id;
                
            }
        });
     });
     $('#print_btn').on('click', function(e) {
      e.preventDefault();
        var CSRF_TOKEN = $('meta[name="csrf-token"').attr('content');
         var order_id = $(this).data('id');
        $.ajax({
           url: base_url+'/admin/print_invoice',
           type: 'POST',
           data: {'_token':csrf_token,'id':order_id},
          success: function(viewContent) {

            $.print(viewContent); // This is where the script calls the printer to print the viwe's content.
          }
        });
      });
   
     $(document).one('click', '.remove_single_item', function(event) {
   
         var order_id = $("#single_item_id").val();
   
         if(order_id == "" || order_id == undefined){
           return false;
         }
   
         $.ajax({
           url: base_url+'/admin/orders/delete',
           type: 'POST',
   
           data: {'_token':csrf_token,'id':order_id}
         })
         .done(function() {
             location.reload();
         })
         .fail(function() {
           alert("some error");
         })
         .always(function() {
           console.log("complete");
         });
     });
    
     $('#newITems').on('submit', function(e){
      e.preventDefault();

      var data = $(this).serialize();
      $.ajax({
         url : "{{route('add_new_invoice',$data['order']->id)}}",
         type: 'POST',
         data: data,
         dataType: 'json',
         success: function(res){
            var html = '';
            
            $('#errorEditBrand').remove();
            $('#successEditBrand').remove();
            if(res.status == false){
               console.log(res);
            }else{
               console.log(res);
            }
            if(res['error'])
            {
               var html = '<div id="errorEditBrand" class="alert alert-danger"><i class="fa fa-times-circle"></i> <strong>Error!</strong>';
               for(var i=0; i < res['error'].length; i++)
               {
                  html += '<p>'+ res['error'][i] +'</p>';
               }
               html += '</div>';
               $('#modal-add-item .modal-header').after(html); 
            }
            
            if(res['success'])
            {  
               html += '<div id="successEditBrand" class="alert alert-success alert-dismissable">';
                  html += '<i class="fa fa-times-circle"></i> <strong>Success!</strong>';
                  html += '<p>'+ res['success'] +'</p>';
               html += '</div>';
               
               $('#modal-add-item .modal-header').after(html);
               $('#newITems')[0].reset();
               // location.reload(true);
               window.location.href = "{{ url('admin/billing_invoice_edit')}}" + '/' + res['id'];

               setTimeout(function(){
                  $('#successEditBrand').remove();

                  // $('#modal-add-item').modal('hide');
               }, 6000);
            }
         }
      });
     })
      $('#send_email').on('submit', function(e){
         e.preventDefault();

         var fd = $( this ).serialize();
             var progressTrigger;
             var progressElem = $('.email_progress .progress-bar');
             // var resultsElem = $('span#results');
             var recordCount = 0;

         $.ajax({
           
           url: "{{url('admin/send_pdf/'.$data['order']->id) }}",
           type: 'POST',
           data: fd,
           dataType: 'json',

           // beforeSend: function(thisXHR){
           //  $('#success').empty();
           //  // progressElem.html(" Waiting for response from server ...");
           //  $('.progress-bar').text('Converting');
           //  console.log(thisXHR);
           //  progressTrigger = setInterval(function () {
           //      if (thisXHR.readyState > 2) {
           //          var totalBytes = thisXHR.getResponseHeader('Content-length');
           //          var dlBytes = thisXHR.responseText.length;
           //          if(totalBytes > 0){
           //           progressElem.text('Sending');
           //           progressElem.css('width', Math.round((dlBytes / totalBytes) * 100) + '%');
           //          }else{
           //           progressElem.text('Sending');
           //           progressElem.css('width', Math.round(dlBytes / 1024) + 'K');

           //          }
           //      }
           //  }, 200);
           // },
           success: function(res){

               // $('.progress-bar').css('width', 0 + '%');
               if(res.success === 1){
                  progressElem.text('Success');
                  progressElem.css('width', '100%');
                  html = '<div class="alert alert-success alert-dismissable">'+
                     '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>'+
                     '<i class="fa fa-check-circle"></i> <strong>'+res.message+'!</strong>'+
                     '<p>Successful</p></div>';
                  $('#success').html(html);
                  $('#error_msg').html('');
                  setTimeout(function() {
                       
                        progressElem.text('');
                        progressElem.css('width', '0%');
                         $('#modal-add-email').modal('hide');
                   }, 500);
               }else{
                  progressElem.text('Error');
                  progressElem.css('width', '0%');
                  html = '<div class="alert alert-success alert-dismissable">'+
                     '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>'+
                     '<i class="fa fa-check-circle"></i> <strong>'+res.message+'!</strong>'+
                     '<p>Successful</p></div>';
                  $('#success').html(html);
                  $('#error_msg').html(html);
               }

            },
            xhr: function(){
               var xhr = $.ajaxSettings.xhr();
               xhr.upload.addEventListener('progress', function(evt){
               if(evt.lengthComputable) {
               var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
                                    progressElem.text('Emails being sent');
                     progressElem.css('width', percentComplete + '%');
               // progressBar.val(percentComplete).text('Loaded' + percentComplete + '%');
               }
               }, false);
               return xhr;
               }


         })
      });
     var pdf_out = "{{ url('/admin/generate_receipt/') }}"; 
     var html = '';

   $('.progressbtn').on('click', function(e){
      var id = $(this).data('id');
         $.ajax({
        type: 'post',
        url: "{{ url('/admin/generate_receipt') }}",
        data: {id: id},
        dataType: 'json',
        beforeSend: function(){
         $('#success').empty();

        },
        uploadProgress:function(event, position, total, percentComplete)
         {

           $('.pdf_progress .progress-bar').text('Converting');
           $('.pdf_progress .progress-bar').css('width', percentComplete + '%');
         },
         success:function(data)
         {
            $('.pdf_progress .progress-bar').text('Converted');
            $('.pdf_progress .progress-bar').css('width', '100%');
            html = '<div class="alert alert-success alert-dismissable">'+
               '<button type="button" data-dismiss="alert" aria-hidden="true" class="close">&times;</button>'+
               '<i class="fa fa-check-circle"></i> <strong>Success!</strong>'+
               '<p>Successful</p></div>';
            $('#success').html(html);   
            setTimeout(function() {
                window.open(data.pdf_route, '_blank');
                $('#modal-generate-receipt').modal('hide');
             }, 1000);
            // $('#modal-generate-receipt').modal('hide');
            
            // window.location.replace(response.pdf_route);
         }

       })

   });
  $('.edit_items').click(function(e){
   // data-target="#modal-edit-domain"
   var id = $(this).data('id');
   
  });
  $('.rd').click(function(){
    $('#domain_text, #bulk_text').hide();

   if($(this).hasClass('rd4')){
       // alert(123); new domain registration
          $('#already_exist_domain').val(0);
         $('#bulk_text').show();
         $('.bulk_availability').show();
         $('.check_availablity').hide();
         $('#domain_text').hide();
   }
   if($(this).hasClass('rd1')){
      $('.check_availablity').hide();
      $('#domain_text').show();
   }
   else{
          // alert(321); already exists domain
         $('#already_exist_domain').val(1);
         $('#domain_text').show();
         $('#bulk_text').hide();
         $('.bulk_availability').hide();
         $('.check_availablity').show();
   }
});
  $('.bulk_availability').click(function(){

  })
  $('.check_availablity').click(function(){
      var search_val = $('#domain_text').val();
      var CSRF_TOKEN = $('meta[name="csrf-token"').attr('content');

      format = /[ !@#$%^&*_+\-=\[\];:\\|,<>\/?]/;
      var result = format.test(search_val);
         if(search_val.trim(' ') != '' && result == false){
            $.ajax({
                 url: base_url+'/admin/check_domain_availablity',
                 type: 'POST',
                 data: {'_token':csrf_token,'search_domain':search_val},
                 dataType: 'Json',
                success: function(data) {
                  console.log(data);
                  $('.single_pricing_table').show();
                  if(data['response'] && data['response']['taken'] !== ''){
                     let error_list = data['response']['error'];
                     var html = '';
                     $.each(error_list, function(k,err){
                        console.log(err.status);
                        if(err.status == 4){
                           html = '<div class="alertymes4 match_nt_found" >'+
                              '<h3 class="light red"><i class="fa fa-times-circle"></i>This is a premium domain name, please contact us at <strong>support@webqom.com</strong> if you wish to register this domain name.</h3>'+
                              '</div>';
                        }else if(err.status == 5){
                           html = '<div class="alertymes4 match_nt_found" >'+
                  '<h5 class="light red"><i class="fa fa-times-circle"></i>Something went wrong.</h5>'+
                    +'</div>';
                        }else if(err.status == 1){
                           html = '<div class="alertymes4 match_nt_found" >'+
                  '<h5 class="light red"><i class="fa fa-times-circle"></i><strong>Sorry</strong> This is already taken!</h5>'+
                    '</div>';

                        }else{
                           html = '<div class="alertymes4 match_nt_found">'+
                   '<h5 class="light red"><i class="fa fa-times-circle"></i>Invalid domain name/extension!</h5>'+
                '</div>';
                        }
                     });

                  }else{
                     html = '<div class="alertymes5 match_found">'+
                        '<h5 class="light"><i class="fa fa-check-circle"></i>Congratulations! <strong class="search_text">Domain</strong> is available!</h5>'+
                   '</div>';
                   let price = JSON.parse(data['response']['price_list']['pricing']);
                     $('#domain_pricing').empty().prepend('<option value="-1">Select Domain Pricing</option>');

                     $.each(price, function(k,val){
                        // console.log('k' + k +' v: '+val.s);
                        var $selected =  (k == 1) ? 'selected="selected"' : "";
                        var $option = $("<option "+$selected+" ></option>").val(k+'-'+val.s).text(k+' Year(s) @RM '+val.s);
                        $("#domain_pricing").append($option).trigger('change');
                     });
                  }
                  $('#domain_status').html(html);
           
                  
                }
              });
         }else{
            alert('error');
         }
      });

     /*$('.search_form').submit(function() {
         if($('input[name="transaction_id"]').val() == "" || $('input[name="id"]').val() == "" ||
            $('input[name="client_name"]').val() == "" || $('input[name="client_id"]').val() == ""){
   
           toastr.success("one of the 4 fields invoice, receipt, client name, client ID are mandatory for Search orders", 'Error');
           return false;
         }
   
     });*/
      /* $(document).on('click', '.empty_cart', function(event) {
       });*/
   });
  document.getElementById("quantity").onkeyup = function() {
   var oldValue = parseInt(this.value);
    if (oldValue > 0) {
            var newVal = parseInt(oldValue);
        } else {
            newVal = 1;
        } 
      document.getElementById("quantity").value = newVal;
      var input = parseInt(this.value);
     if (input < 0 || input > 100)
       console.log("Value should be between 0 - 100");
     return;
   }   
   function getInvoiceItem(id) {
          //clear input
          $.ajax({
              type: 'GET',
              url: base_url+'/admin/blog/articles/'+id,
              contentType: 'json',
              headers: {
                  'X-CSRF-Token': csrf_token
              }
          })
              .done(function(res){
                  if (res.error == 1) {
                      location.reload();
                  }

                  if (res.error == 0) {
                      // show de default
                      $('.author-thumbnail-edit').removeAttr('style');
                      $('.front_image-edit').removeAttr('style');

                      var article = res.data.article;
                      if (article.status == 1) {
                          $('.status-form-group').empty().append('<label class="col-md-3 control-label">Status</label><div class="col-md-6"><div data-on="success" data-off="primary" class="make-switch-init-edit"><input type="checkbox" name="status" id="status_edit" checked/></div></div>');
                          $('.make-switch-init-edit').bootstrapSwitch();
                      } else {
                          $('.status-form-group').empty().append('<label class="col-md-3 control-label">Status</label><div class="col-md-6"><div data-on="success" data-off="primary" class="make-switch-init-edit"><input type="checkbox" name="status" id="status_edit"/></div></div>');
                          $('.make-switch-init-edit').bootstrapSwitch();
                      }

                      $('#title_edit').empty().html(article.title);
                      $('#description_edit').empty().html(article.description);
                      $('#post_date_edit').empty().val(article.frontend_date_format);
                      $('#author_edit').empty().val(article.author);
                      $('#content_edit').empty().html(article.content);
                      if (article.author_thumbnail != '') {
                          $('.author_thumbnail_edit_link').attr('src', base_url+'/storage/articles/author_thumbnail/'+article.author_thumbnail);
                      } else {
                          $('.author-thumbnail-edit').css('display', 'none');
                      }

                      if (article.front_image != '') {
                          $('.front_image_edit_link').attr('src', base_url+'/storage/articles/front_image/'+article.front_image);
                      } else {
                          $('.front_image-edit').css('display', 'none');
                      }
                      $('#update_id').val(article.id);
                      //assign value for delete author thumbnail
                      $('#update_icon_id').val(article.id);
                      //assign value for delete front image
                      $('#update_front_image_id').val(article.id);
                  }
              })
              .error(function(err){
                  console.log(err);
              });
      }
      function load_countries(){
          var country_id=$( "#country option:selected" ).val();
          $("#state").html("<option value=''>-- Please select --</option>");
          $("#city").html("<option value=''>-- Please select --</option>");
          var stateid = $('.state_id').val();
          var cityid = $('.city_id').val();
          $.ajax({
            url: '/get_state/' + country_id,
            type: 'GET',
            dataType: 'json',
          })
          .done(function(response) {
            
            var state_selected="";
            for (var i=0; i < response.length; i++) {
              if (response[i].id == stateid) {
                $("#state").append(
                  $("<option>" , {
                    text: response[i].name,
                    value:  response[i].id,
                    selected:  "selected"
                  })
                  );
                
              }else{
                $("#state").append(
                  $("<option>" , {
                    text: response[i].name,
                    value:  response[i].id,
                  })
                  );
              }

            }
            var state_id=$( "#state option:selected" ).val();
            $("#city").html("<option value=''>-- Please select --</option>");
            $.ajax({
              url: '/get_city/' + stateid,
              type: 'GET',
              dataType: 'json',
            })
            .done(function(response) {
              var city_selected="";
              
              for (var i=0; i < response.length; i++) {
                if (response[i].id==cityid) {
                  $("#city").append(
                    $("<option>" , {
                      text: response[i].name,
                      value:  response[i].id,
                      selected:  "selected"
                    })
                    )
                }else{
                  $("#city").append(
                    $("<option>" , {
                      text: response[i].name,
                      value:  response[i].id,
                    })
                    )
                }


              }
            })
            .fail(function() {
            })
            .always(function() {
            });
          })
          .fail(function() {
          })
          .always(function() {
          });

        }    
        
</script>

@endsection
@endsection