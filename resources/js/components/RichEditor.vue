<template>
    <editor
        :id="id"
        :name="name"
        api-key="ckeza80cam7bzk1f8tjrz6vw4dutksq5ln2m9cpo032fta73"
        initial-value="Write Your Post Content Here!"
        :value="content"
        :init="{
            height: 500,

            menubar: true,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar:
                'undo redo | formatselect | bold italic backcolor | table \
                alignleft aligncenter alignright alignjustify | \
                bullist numlist outdent indent | removeformat | help'
        }"
    />
</template>
<script>
    import Editor from '@tinymce/tinymce-vue';

    export default {
        props: ['id', 'name', 'content'],
        components: {
            'editor': Editor,
        }
    };
</script>
