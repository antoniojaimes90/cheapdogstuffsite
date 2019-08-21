 <template>
    <div>
        <div id="header" >
            <div id="logo" class="container pt-2">
                <img :src="img" alt="">
            </div>
           
        </div>
       <div class="container">
            <vue-good-table
                :columns="columns"
                :search-options="{
                    enabled: true}"
                :pagination-options="{
                    enabled: true
                }"
                :rows="rows">
            <template slot="table-row" slot-scope="props">
                <div v-if="props.column.field === 'Category'">
                    {{props.row.Category}}
                </div>
                <div v-if="props.column.field === 'Name'">
                    <span>
                        <img :src="props.row.img" alt="">
                    </span>
                    <span>
                        {{props.row.Name}}
                    </span>
                </div>
                <div v-if="props.column.field === 'Price'">
                    <span>{{props.row.Price}}</span>
                </div>
                <div v-if="props.column.field === 'store'">
                    <span>{{props.row.store}}</span>
                </div>
                <div v-if="props.column.field === 'Link'">
                    <a :href="props.row.Link" target="_blank" class="background-gray">Link</a>
                </div>
            </template>
        </vue-good-table>           
       </div>
    </div>    
</template>
<style scope>
    #header{
        height: 100px; background-color: #6B7A8F; margin-bottom: 40px;
    }
    #logo{
        
    }
</style>
<script>
import logo from "./../../public/img/logo.png"
export default {
    data(){
        return {
            img: logo,
            columns: [
                {
                    label: 'Category',
                    field: 'Category'
                },        
                {
                    label: 'Name',
                    field: 'Name'
                },
                {
                    label: 'Price',
                    field: 'Price'
                },
                {
                    label: 'Store',
                    field: 'store'
                },
                {
                    label: 'Link',
                    field: 'Link'
                }
            ],
            rows: [

            ]
        }
    },
    methods: {
        getData(){
            this.$http.get('/product').then(response => {
                if (response.status == 200) {
                    console.log(response.data)
                    this.rows = response.data;
                };
            });
        }
    },
    mounted(){
        this.getData();
    },

}
</script>
