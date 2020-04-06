<template>
    <div class="container">
            <div style="margin: 20px">
                <input type="text" class="login-input" placeholder="Username" v-model="form.username">
                <input type="password" class="login-input" placeholder="Password" v-model="form.password" >

                <div class="extra-container" style="margin-bottom: 16px">
                        <el-button @click="authenticate" class="button6" style="font-weight: bold;">Login</el-button>
                </div>
                <div class="form-group row" v-if="authError">
                    <p class="error">
                        {{ authError }}
                    </p>
                </div>
            </div>
    </div>

    <!-- <div class="login row justify-content-center">
         <div class="col-md-6">
             <div class="col-md-12">
                 <div class="card">
                     <div class="card-header"> Login</div>
                     <div class="card-body">
                         <el-form  :model="form"  ref="form"   @submit.prevent.native="authenticate" >
                             <el-form-item label="Username" prop="username" >
                                 <el-input   v-model="form.username" clearable></el-input>
                             </el-form-item>
                             <el-form-item label="Password" prop="password" >
                                 <el-input   v-model="form.password" :type="passwordFieldType" clearable>
                                     <el-button slot="append" @click="switchVisibility" icon="el-icon-view"></el-button>
                                 </el-input>
                             </el-form-item>
                             <el-form-item>
                                 <div class="float-right ">
                                     <el-button  @click="authenticate">Login</el-button>
                                 </div>
                             </el-form-item>
                             <div class="form-group row" v-if="authError">
                                 <p class="error">
                                     {{ authError }}
                                 </p>
                             </div>
                         </el-form>

                     </div>
                 </div>
             </div>
         </div>

     </div> -->
</template>

<script>
    import {login} from '../../helpers/auth';
    import axios from "axios";
    import moment from "moment";
    export default {
        name: "login",
        data() {
            return {
                form: {
                    username: '',
                    password: ''
                },
                error: null,

                password: '',
                passwordFieldType: 'password'
            };
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');
                login(this.$data.form)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/'});
                    })
                    .catch((error) => {
                        this.$store.commit("loginFailed", {error});
                    });
            },
            switchVisibility() {
                this.passwordFieldType = this.passwordFieldType === 'password' ? 'text' : 'password'
            },
            setBackground(){
                axios.get('/images/backgrounds.json').then(response => {
                    this.jsonObjects = response.data;
                    this.ind = Math.floor(Math.random() * 40);
                    var url = this.jsonObjects[this.ind].url;
                    localStorage.setItem("background", JSON.stringify(url));
                    $('body').css('background-image', 'url('+url+')');
                });
                localStorage.setItem("current", JSON.stringify(moment().format('dddd')));
            },
        },
        created() {
            this.setBackground();
        },
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        }
    }
</script>

<style lang="scss" scoped>
    .error {
        text-align: center;
        color: red;
    }
    .login-input {
        font-weight: bold;
        border: 1px solid white;
        background-color: transparent;
        padding: 10px 18px;
        width: 60%;
        font-size: 18px;
        margin-bottom: 16px;
        border-radius: 6px;

        &:focus {
         outline: none;
     }
    border-bottom: 6px solid #CCC;
    }
    input[type="text"]
    {
        background: transparent;
        border: none;
    }
    input[type="password"]
    {
        background: transparent;
        border: none;
    }
    .button6 {
        background-color: white;
        color: black;
        font-weight: bold;
        border: 1px solid white;
    }
    .button6:hover {
        background-color: transparent;
        color: white;
    }
</style>
