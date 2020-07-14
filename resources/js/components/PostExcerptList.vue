<template>
    <section class="post-list">
        <slot></slot>

        <div class="text-center" v-show="loading">
            <div class="text-center">
                <div class="spinner-grow" role="none"></div>
                &nbsp;
                <div class="spinner-grow" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                &nbsp;
                <div class="spinner-grow" role="none"></div>
            </div>
        </div>

        <div class="row" v-show="!loading">
            <post-excerpt v-for="post in posts" v-bind:key="post.id" :href="post.href" :title="post.title">
                {{post.excerpt}}
            </post-excerpt>
        </div>

        <div class="row" v-show="!loading">
            <div class="col-12">
                <section class="pagination">
                    <nav aria-label="Page Navigation" class="mx-auto">
                        <ul class="pagination m-0">
                            <li v-for="link in links" v-show="link.show">
                                <a v-show="!link.current" class="page-link" :href="link.href"
                                   @click.prevent="jumpPage(link.page)">{{link.text}}</a>
                                <a v-show="link.current" class="page-link">{{link.text}}</a>
                            </li>
                        </ul>
                    </nav>
                </section>
            </div>
        </div>
    </section>
</template>
<script>
    export default {
        props: ['page', 'maxPage', 'baseUrl'],

        data: function() {
            return {
                posts: [],
                currentPage: 1,
                loading: false,
            };
        },

        computed: {
            links() {
                return [
                    this.generateLink(this.currentPage - 1, 'Previous'),
                    this.generateLink(this.currentPage - 2),
                    this.generateLink(this.currentPage - 1),
                    this.generateLink(this.currentPage),
                    this.generateLink(this.currentPage + 1),
                    this.generateLink(this.currentPage + 2),
                    this.generateLink(this.currentPage + 1, 'Next'),
                ];
            },
        },

        created() {
            this.get(parseInt(this.page));

            window.addEventListener('popstate', e => {
                this.posts = e.state.posts;
                this.currentPage = e.state.currentPage;
            });
        },

        methods: {
            generateLink(page, text = null) {
                let link = {
                    show: false,
                    text: text,
                    page: page,
                    current: false,
                    href: `${this.baseUrl}/${page}`,
                };

                if (page === 1) {
                    link.href=this.baseUrl;
                }

                if (page === this.currentPage) {
                    link.current = true;
                }

                if (page >= 1 && page <= this.maxPage) {
                    link.show = true;
                }

                if (text === null) {
                    link.text = link.page;
                }

                return link;
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
                axios.get(`${this.baseUrl}${page}`).then(data => {
                    this.loading = false;
                    this.currentPage = page;
                    this.posts = data.data;
                    window.history.pushState({posts: this.posts, currentPage: this.currentPage}, '',
                        this.baseUrl + (this.currentPage > 1 ? `${this.currentPage}` : ''));
                    window.scrollTo(0, 0);
                });
            },
        },
    };
</script>
