<template>
    <div class="container">
        <div class="row justify-content-center mytable">
            <h3 class="p-5">Here you can see all logged time</h3>

            <el-form :inline="true" :model="filterForm"  ref="filterForm" >
                <el-row>
                    <el-form-item label="" prop="startDate">
                        <el-date-picker
                            v-on:keyup.native.enter="onSubmit"
                            @change="onSubmit"
                            v-model = "filterForm.startDate"
                            type = "date"
                            format = "yyyy/MM/dd"
                            value-format = "yyyy-MM-dd"
                            placeholder = "Start Date">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="" prop="endDate">
                        <el-date-picker
                            v-on:keyup.native.enter="onSubmit"
                            @change="onSubmit"
                            v-model = "filterForm.endDate"
                            type = "date"
                            format = "yyyy/MM/dd"
                            value-format = "yyyy-MM-dd"
                            placeholder = "End Date">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item prop="status" label="" >
                        <el-select name="status" v-model="filterForm.status" @change="onSubmit">
                            <el-option label="Show All" value="1" selected></el-option>
                            <el-option label="Only Me" value="2"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="" prop="search">
                        <el-input v-model="filterForm.search" @change="onSubmit" placeholder="Search"  >
                            <el-button slot="append" @click="onSubmit" icon="el-icon-search"></el-button>
                        </el-input>
                    </el-form-item>
                </el-row>
            </el-form>


            <div class="col-md-12">
                <el-table v-loading="loading"  @sort-change="sortChange" max-height="800" :data="result"  >
                    <el-table-column label="Name" prop="name" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                    <el-table-column label="Username"  prop="username" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                    <el-table-column label="Department"  prop="department" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                    <el-table-column label="Check In"  prop="check_in" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                    <el-table-column label="Check Out" prop="check_out" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                    <el-table-column label="Hours"  prop="hours" sortable="custom"  :sort-orders="sort_orders"> </el-table-column>
                </el-table>
                <div class="text-center" >
                    <br>
                    <el-pagination background layout="prev, pager, next"
                                   :total="pagination.total"
                                   :current-page.sync="pagination.current_page"
                                   :page-size="pagination.per_page"
                                   @current-change="paginationCurrentChange">
                    </el-pagination>
                    <br>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'


    export default {
        name: 'list',
        data() {
            return {
                result     : [],
                pagination : {},
                loading: false,
                id     : 0,
                sort_orders : ['ascending', 'descending'],
                sort_field : '',
                sort_order : '',
                filterForm: {
                    search   : '',
                    status   : '',
                    startDate  : '',
                    endDate    : ''
                },
                search :    0,
                date_from:  0,
                date_to :   0,
                status  :   0,
            };
        },
        methods: {
            getAll(page = 1, date_from = this.date_from, date_to = this.date_to, search = this.search, id = this.id,  sort_field = this.sort_field, sort_order = this.sort_order)
            {
                axios.get('/api/getall?page=' + page + '&date_from=' + date_from+'&date_to='+date_to+'&search='+search+'&id='+id+'&sort_field='+sort_field+'&sort_order='+sort_order)
                    .then(res => {
                       this.pagination =
                           {
                               "current_page": res.data.current_page,
                               "per_page": parseInt(res.data.per_page),
                               "total": res.data.total
                           };
                       this.loading = false;
                       this.result = res.data.data;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            paginationCurrentChange(val) {
                this.loading = true;
                this.getAll(val);
            },
            sortChange: function (column) {
                this.loading = true;
                this.sort_field = column.prop;
                this.sort_order = column.order;
                this.getAll();
            },
            onSubmit(){
                this.search  = (this.filterForm.search  !== '') ? this.filterForm.search : 0;
                this.date_from = (this.filterForm.startDate !== null) ? this.filterForm.startDate : 0;
                this.date_to   = (this.filterForm.endDate !== null) ? this.filterForm.endDate : 0;
                if(this.filterForm.status == 2)
                    this.id = this.$store.getters.currentUser.id;
                else
                    this.id = 0;

                this.loading   = true;
                this.getAll();
            }
        },
        mounted: function () {
                this.loading = true;
                this.getAll();


        },
        created() {

        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        },



    }
</script>

<style scoped>

</style>
