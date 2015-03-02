<?php
/* @var $this yii\web\View */
?>
<form name="form">
    <div class="box box-primary">
        <div class="box box-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group ">
                        <label for="number" class="control-label">Number</label>
                        <span class="form-control">{{model.number}}</span>

                        <div class="help-block"></div>
                    </div>
                    <div class="form-group required" ng-class="{error:true}">
                        <label for="purchase-nmsupplier" class="control-label">Nm Supplier</label>
                        <input type="text" class="form-control" ng-model="model.supplier" name="supplier"
                               typeahead="supplier as supplier.name for supplier in masters.suppliers | filter:$viewValue | limitTo:8">

                        <div class="help-block"></div>
                    </div>
                    <div class="form-group field-purchase-nmstatus">
                        <label for="purchase-nmstatus" class="control-label">Nm Status</label>
                        <span class="form-control">{{model.status}}</span>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-xs-6">                
                    <div class="form-group field-purchase-date required">
                        <label for="purchase-date" class="control-label">Date</label>
                        <p class="input-group" style="width: 50%;">
                            <input type="text" class="form-control" datepicker-popup="{{dt.format}}" 
                                   ng-model="model.date" is-open="dt.opened" datepicker-options="dt.dateOptions" 
                                   ng-focus="dt.open($event)"
                                   ng-required="true" close-text="Close" />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="dt.open($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </p>
                        <div class="help-block"></div>
                    </div>
                    <h4 style="display: none; padding-left: 135px;" id="bfore">Rp<span id="purchase-val">0</span>-<span id="disc-val">0</span></h4>         
                    <h2 style="padding-left: 133px; margin-top: 0px;">Rp<span id="total-price">0</span></h2>
                </div>
            </div>
        </div>
        <div class="box box-footer">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-xs-10">
                            Product :
                            <input type="text" class="form-control" ng-model="selectedProduct" id="product"
                                   typeahead="product as product.name for product in masters.products | filter:$viewValue | limitTo:8"
                                   ng-keypress="changeProduct($event, $viewValue)"
                                   typeahead-on-select="selectProduct($item)"
                                   >
                        </div>
                        <div class="col-xs-2">
                            Item Discount:
                            <input type="text" class="form-control" name="diskon" mdm-validation="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <table class="tabular table-striped col-lg-12">
                        <thead style="background-color: #9d9d9d;">
                            <tr><th class="col-lg-4">Product</th>
                                <th class="col-lg-1">Qty</th>
                                <th class="col-lg-2">Uom</th>
                                <th class="col-lg-2">@Price</th>

                                <th class="col-lg-2">Sub Total</th>
                                <th class="col-lg-1">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody id="detail-grid">
                            <tr ng-repeat="(idx,detail) in model.details" data-key="{{idx}}" on-last-repeat="setFokusQty()">
                                <td >{{detail.product.name}}</td>
                                <td ><input ng-model="detail.qty" class="form-control" data-field="qty"></td>
                                <td ><select ng-model="detail.uom_id" class="form-control" data-field="uom"
                                            ng-options="uom.id as uom.name for uom in detail.product.uoms">
                                    </select></td>
                                <td ><input ng-model="detail.price" class="form-control" data-field="price"></td>
                                <td >{{subTotal(detail)}}</td>
                                <td><a href="javascript:;" ng-click="deleteRow(idx)"><i class="glyphicon glyphicon-trash"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</form>