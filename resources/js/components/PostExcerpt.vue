<template>
    <div class="col-12 mb-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><slot name="title"></slot></h3>
            </div>
            <div class="card-body">
                <p><slot></slot></p>
                <p class="text-right"><a :href="href">Read More...</a></p>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['href']
    }
</script>
