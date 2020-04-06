<template>
    <div class="container">
        <div id="myModal" class="modal">
            <div class="modal-content">
                <p>Sorry, One or more pages are already open. Please use them.</p>
            </div>
        </div>
        <div class="field created-at clock" style="font-size: 22px"> {{ timeDisplay }}</div>
        <div class="field created-at" style="font-size: 16px"> {{ dateDisplay }}</div>
        <p id="timer" >{{timer_value}}</p>
        <div class="row justify-content-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="row content" style="">
                        <div class="col-md-12">
                            <div class="button-wrapper btn-container-center">
                                <el-button  type="success" @click="addCheckIn" :disabled="checkedin" class="effect8" icon="el-icon-video-play">Start</el-button>
                                <el-button type="danger" @click="addCheckOut" :disabled="!checkedin" class="effect8" icon="el-icon-video-pause">Stop</el-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrapper">
            <div style="margin: 20px">
            <input type="text" class="todo-input" placeholder="What is your main focus for today?" v-model="newTodo" @keyup.enter="addTodo">
                <p>This list is private and never shared</p>
            <transition-group name="fade"  leave-active-class="animated fadeOutDown">
                <div v-for="(todo, index) in todosFiltered" :key="todo.id" class="todo-item">
                    <div class="todo-item-left">
                        <input type="checkbox" v-model="todo.completed" @change="completeTodo(todo.id, todo.completed)">
                        <div  class="todo-item-label" :class="{ completed : todo.completed }">{{ todo.title }}</div>
                    </div>
                    <div class="remove-item" @click="removeTodo(todo.id)"> <i class="el-icon-circle-close"></i></div>
                </div>
            </transition-group>

            <div class="extra-container" style="margin-bottom: 16px">
                <div v-if="showTodoButton">
                    <el-button @click="filter = 'all'" class="button5" style="font-weight: bold;">All</el-button>
                    <el-button @click="filter = 'active'" class="button5">Active</el-button>
                    <el-button @click="filter = 'completed'" class="button5">Completed</el-button>
                </div>
                <div>
                    <transition name="fade">
                        <el-button v-if="showClearCompletedButton" @click="clearCompleted" class="button5">Clear Completed</el-button>
                    </transition>
                </div>
            </div>
            </div>
        </div>
        <div class="box ">
            <h6 style="padding: 6px; font-weight: bold;" v-if="users.length>0">Active Users:</h6>
            <h6 style="padding: 6px; font-weight: bold" v-else>No Active Users</h6>
            <ul class="ul-users">
                <li v-for="item in users" :key="item.id" class="li-left">
                    <p> <i class="el-icon-user" style="color: green;"></i>  {{item.username}}</p>
                </li>
            </ul>
        </div>
        <div class="wrapper">
            <h4>{{quote}}</h4>
            <h6>{{author}}</h6>
        </div>

    </div>
</template>

<script>
    import axios from 'axios'
    import moment from 'moment'

    export default {
        name: 'home',
        data() {
            return {
                userId    : null,
                checkedin : false,
                recordId  : '',
                check_in  : null,
                timer_value : '',
                start : '',
                timeDisplay : '',
                dateDisplay : '',
                users: [],
                dot :  "•",
                newTodo: '',
                filter: 'all',
                todos: [],
                jsonObjects: [],
                quotesObjects : [],
                ind : 0,
                quote : '',
                author: ''
            };
        },
        timers: {
            log: { time: 1000, autostart: false, repeat:true, immediate: true },
            current: { time: 60000, autostart: true, repeat:true, immediate: true },
            altermsg: { time: 7200000, autostart: true, repeat:true, immediate: false },
        },
        directives: {
            focus: {
                inserted: function (el) {
                    el.focus()
                }
            }
        },
        methods: {
            completeTodo(id, completed)
            {
                axios.post('/api/updateTodo?id=' + id +"&completed=" + completed)
                    .then(res => {
                        //console.log(res.data)
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            log () {
                //var start =  this.check_in ? new Date(this.check_in).getTime() : new Date().getTime();
                var date = new Date();
                var now = new Date(date.toLocaleString('en-US', {timeZone: "Asia/Tbilisi"})).getTime();
                //now = now.getTime();
                var distance = now - this.start;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                if(days>=0 && hours>=0 && minutes>=0)
                    this.timer_value = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            },
            altermsg()
            {
               /* if(this.checkedin)
                {
                    var date = new Date();
                    var now =  new Date(date.toLocaleString('en-US', {timeZone: "Asia/Tbilisi"})).getTime();

                    //var now = new Date().getTime();
                    var distance = now - this.start;
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    if(hours>=2 && hours<4)
                        alert("You need to stretch you legs! You've been working more than "+ hours +" hours");
                    else if(hours>=4 && hours<6)
                        alert("You've been working more than "+ hours +" hours. Let's have a cup of coffee");
                    else if(hours>=6 && hours<8)
                        alert("Wow! You've been working more than "+ hours +" hours. Let's take a break");
                    else if (hours>=8 && hours<10)
                        alert("Good job! You've been working more than "+ hours +" hours.");
                }*/
                /*var answer = window.confirm("You need to stratch you legs! You have been working more than two hours")
                if (answer) {
                    console.log("wow")
                }
                else {
                    console.log("no")
                }*/
            },
            current(){
                this.timeDisplay = moment().format('LT');
                this.dateDisplay = moment().format('dddd') + " " + moment().format('LL');
            },
            getLastLog()
            {
                    axios.get('/api/lastLog?user_id=' + this.$store.getters.currentUser.id)
                        .then(res => {
                            if(res.data.length > 0)
                            {
                                this.recordId = res.data[0]['id'];
                                this.check_in = res.data[0]['check_in'];
                                this.start = new Date(this.check_in).getTime();
                                this.$timer.start('log');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        })
            },
            getActiveStatus()
            {
                axios.get('/api/getUserByid?id=' + this.$store.getters.currentUser.id)
                    .then(res => {
                        if(res.data[0].active === 1)
                        {
                            this.checkedin = true;
                            this.getLastLog();
                        }
                        else
                        {
                            this.checkedin = false;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            addCheckIn()
            {
                var date = new Date();
                this.start  =  new Date(date.toLocaleString('en-US', {timeZone: "Asia/Tbilisi"})).getTime();
                this.checkedin=true;
                this.$timer.start('log');
                this.updateStatus(this.$store.getters.currentUser.id, 1);

                axios.post('/api/addcheckin?user_id=' + this.$store.getters.currentUser.id +"&name=" + this.$store.getters.currentUser.name)
                    .then(res => {
                        this.recordId = res.data;
                        this.getActiveUsers();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            addCheckOut()
            {
                this.checkedin=false;
                this.$timer.stop('log');
                this.updateStatus(this.$store.getters.currentUser.id, 0);
                axios.post('/api/addcheckout?user_id=' + this.$store.getters.currentUser.id + '&id=' + this.recordId+"&name=" + this.$store.getters.currentUser.name)
                    .then(res => {
                        this.check_in  = null;
                        this.getActiveUsers();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            updateStatus(id, active)
            {
                axios.post('/api/updateStatus?id=' + id +"&active=" + active )
                    .then(res => {
                        //console.log(res.data);
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            getActiveUsers()
            {
                axios.get('/api/activeUsers' )
                    .then(res => {
                        if(res.data.length > 0)
                        {
                                this.users = res.data;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            getAllTodos()
            {
                axios.get('/api/getAllTodos?user_id=' + this.$store.getters.currentUser.id)
                    .then(res => {
                        this.todos = res.data;
                        for(var i=0; i<res.data.length; i++)
                        {
                            if(this.todos[i].completed == 1)
                            {
                                this.todos[i].completed = true;
                            }
                            else
                            {
                                this.todos[i].completed = false;
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            addTodo()
            {
                axios.post('/api/addTodo', {user_id:this.$store.getters.currentUser.id, title: this.newTodo })
                    .then(res => {
                        this.newTodo = ''
                        this.getAllTodos();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            removeTodo(index) {
                axios.delete('/api/removeTodo?id=' + index)
                    .then(res => {
                        this.getAllTodos();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            clearCompleted() {
                var ids = [];
                var completedTodos = this.todos.filter(todo => todo.completed);
                for(var i=0; i<completedTodos.length; i++)
                {
                    ids[i] = completedTodos[i].id;
                }

                axios.post('/api/removeCompletedTodos', {ids: ids})
                    .then(res => {
                        this.getAllTodos();
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            showModal(){
                localStorage.openpages = Date.now();
                var onLocalStorageEvent = function(e){
                    if(e.key == "openpages"){
                        localStorage.page_available = Date.now();
                    }
                    if(e.key == "page_available"){
                        var modal = document.getElementById("myModal");
                        modal.style.display = "block";
                    }
                };
                window.addEventListener('storage', onLocalStorageEvent, false);
            },
            setQuote(){
                axios.get('/data/quotes.json').then(response => {
                    this.quotesObjects = response.data;
                    var quote_id = Math.floor(Math.random() * 40);
                    this.quote  = this.quotesObjects[quote_id].quoteText;
                    this.author = this.quotesObjects[quote_id].quoteAuthor;
                    localStorage.setItem("quote", JSON.stringify(this.quote));
                    localStorage.setItem("author", JSON.stringify(this.author));
                });
                localStorage.setItem("current_quote", JSON.stringify(moment().format('dddd')));
            },
            changeQuote(){
                const currentStr = localStorage.getItem("current_quote");
                if(currentStr) {
                    var cur = JSON.parse(currentStr);
                    if(cur !== moment().format('dddd'))
                    {
                        this.setQuote();
                    }
                    else
                    {
                        const quoteStr = localStorage.getItem("quote");
                        const authorStr = localStorage.getItem("author");
                        if(quoteStr)
                        {
                            this.quote = JSON.parse(quoteStr);
                            if(authorStr)
                                this.author = JSON.parse(authorStr);
                        }
                        else
                        {
                            this.setQuote();
                        }
                    }
                }
                else{
                    this.setQuote();
                }
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
            changeBackground(){
                const currentStr = localStorage.getItem("current");
                if(currentStr) {
                    var cur = JSON.parse(currentStr);
                    if(cur !== moment().format('dddd'))
                    {
                        this.setBackground();
                    }
                    else
                    {
                        const bgStr = localStorage.getItem("background");
                        if(bgStr)
                        {
                            var bg = JSON.parse(bgStr);
                            $('body').css('background-image', 'url('+ bg +')');
                        }
                        else
                        {
                           this.setBackground();
                        }
                    }
                }
                else{
                    this.setBackground();
                }
            },
        },
        watch: {
        },
        mounted: function () {
        },
        created() {
            this.showModal();
            this.getAllTodos();
            //this.getLastLog();
            this.getActiveStatus();
            this.getActiveUsers();
            this.changeBackground();
            this.changeQuote();
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
            todosFiltered() {
                if (this.filter == 'all') {
                    return this.todos
                } else if (this.filter == 'active') {
                    return this.todos.filter(todo => !todo.completed)
                } else if (this.filter == 'completed') {
                    return this.todos.filter(todo => todo.completed)
                }
                return this.todos
            },
            showClearCompletedButton() {
                return this.todos.filter(todo => todo.completed).length > 0
            },
            showTodoButton() {
                return this.todos.length > 0;
            }
        }
    }
</script>

<style lang="scss">
    @import '../../styles/mystyles.scss';
    @import url("https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css");

    body{
        color:white;
        background-size: cover;
        font-weight: bold;
        background-repeat: no-repeat;
        background-position: center center;
        height: 100vh;
    }



</style>
