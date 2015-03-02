<div class="branch-index">
    <div class="btn-group">
        <a href="#/create" class="btn btn-success btn-sm"><i class="fa fa-plus-square"></i></a>
    </div>
    <div class="box box-info">
        <div class="box-body no-padding">
            <div >
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><a >Number</a></th>
                            <th><a >Supplier</a></th>
                            <th><a >Branch</a></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-key="{{model.id}}" ng-repeat="(no,model) in rows">
                            <td><a ng-href="#/view/{{model.id}}">{{(pager.currentPage-1)*pager.itemPerPage + no + 1}}</a></td>
                            <td>{{model.number}}</td>
                            <td>{{model.supplier.name}}</td>
                            <td>{{model.branch.name}}</td>
                        </tr>
                    </tbody>
                </table>
                <pagination total-items="pager.totalItems" ng-model="pager.currentPage" 
                            max-size="pager.maxSize" items-per-page="pager.itemPerPage"
                            ng-change="pageChange()"
                            class="pagination-sm" boundary-links="true"></pagination>
            </div>        
        </div>
    </div>
</div>
