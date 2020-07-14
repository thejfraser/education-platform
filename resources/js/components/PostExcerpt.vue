<template>
    <div class="col-12 mb-5 pb-5 border-bottom">
        <h2 class="card-title">{{title}}</h2>
        <div class="text-muted">
            <p><slot></slot></p>
            <div class="text-right"><a :href="href" class="btn btn-sm btn-outline-primary">Read More...</a></div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['href', 'title']
    }
</script>
