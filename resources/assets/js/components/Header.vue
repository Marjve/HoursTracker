<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                <a href="/" class="navbar-brand router-link-exact-active router-link-active">Support Hours Tracking</a>
            </router-link>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <template v-if="!currentUser">
                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <router-link to="/showall" class="nav-link">Show All</router-link>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ currentUser.name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout() {
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            }
        }
    }
</script>
<style scoped>
    .navbar-laravel a {
        color: white !important;
    }
    .navbar-laravel {
        background-color: rgba(0,0,0,.4) !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
        color: white;
    }
    .dropdown-menu.show {
        background-color: rgba(0,0,0,.4);
        margin-top: 7px;
        border-top: 1px solid black;
    }
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: #2f2f2f73;
    }
</style>
