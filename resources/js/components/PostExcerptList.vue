<template>
    <section class="post-list">
        <slot></slot>
        <div class="jumbotron text-center" v-show="loading">
            <p class="display-4">Loading</p>
        </div>
        <div class="row" v-show="!loading">
            <post-excerpt v-for="post in posts" v-bind:key="post.id" :href="post.href">
                <template v-slot:title="title">{{post.title}}</template>
                {{post.excerpt}}
            </post-excerpt>
        </div>
        <div class="row" v-show="!loading">
            <div class="col-12">
                <section class="pagination bg-light">
                    <nav aria-label="Page Navigation" class="mx-auto">
                        <ul class="pagination m-0">
                            <li class="page-item" v-show="currentPage > 1"><a class="page-link" href="#prev" @click.prevent="prevPage">Previous</a></li>
                            <li class="page-item" v-show="pageLess2 > 0"><a class="page-link" href="#less2" @click.prevent="jumpPage(pageLess2)">{{pageLess2}}</a></li>
                            <li class="page-item" v-show="pageLess1 > 0"><a class="page-link" href="#less1" @click.prevent="jumpPage(pageLess1)">{{pageLess1}}</a></li>
                            <li class="page-item"><a class="page-link">{{currentPage}}</a></li>
                            <li class="page-item" v-show="pageMore1 <= maxPage"><a class="page-link" href="#more1" @click.prevent="jumpPage(pageMore1)">{{pageMore1}}</a></li>
                            <li class="page-item" v-show="pageMore2 <= maxPage"><a class="page-link" href="#more2" @click.prevent="jumpPage(pageMore2)">{{pageMore2}}</a></li>
                            <li class="page-item" v-show="currentPage < maxPage"><a class="page-link" href="#next" @click.prevent="nextPage">Next</a></li>
                        </ul>
                    </nav>
                </section>
            </div>
        </div>
    </section>
</template>
<script>
    export default {
        props: ['page', 'maxPage'],

        data: function() {
            return {
                posts: [],
                currentPage: 1,
                loading: false
            };
        },

        computed: {
            pageLess2() {
                return this.currentPage - 2;
            },
            pageLess1() {
                return this.currentPage - 1;
            },
            pageMore1() {
                return this.currentPage + 1;
            },
            pageMore2() {
                return this.currentPage + 2;
            }
        },

        created() {
            this.get(parseInt(this.page));

            window.addEventListener('popstate', e => {
                this.posts = e.state.posts;
                this.currentPage = e.state.currentPage;
            });
        },

        methods: {
            nextPage() {
                if (this.currentPage >= this.maxPage) {
                    return false;
                }
                this.get(this.currentPage + 1);
            },
            prevPage() {
                if (this.currentPage <= 1) {
                    return false;
                }
                this.get(this.currentPage - 1);
            },
            jumpPage(page) {
                if (parseInt(page) === this.currentPage) {
                    return false;
                }
                this.get(page);
            },
            get(page) {
                if (this.loading) {
                    return false;
                }

                this.loading = true;
                axios.get('/posts/' + page).then(data => {
                    this.loading = false;
                    this.currentPage = page;
                    this.posts = data.data;
                    window.history.pushState({posts: this.posts, currentPage: this.currentPage}, '', '/posts/' + (this.currentPage > 1 ? this.currentPage : ''));
                    window.scrollTo(0, 0);
                });
            },
        },
    };
</script>
