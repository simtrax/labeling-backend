<template>
    <div>
            
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" class="form-control" v-model="title">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" rows="3" class="form-control" v-model="description"></textarea>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="minZoom">Minimum zoom</label>
                    <input type="number" id="minZoom" class="form-control" v-model.number="minZoom">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="maxZoom">Maximum zoom</label>
                    <input type="number" id="maxZoom" class="form-control" v-model.number="maxZoom">
                </div>
            </div>
        </div>

        <vue-dropzone id="dropzone" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-success="projectCreated" :options="dropzoneOptions">
        </vue-dropzone>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {

        name: 'project-create',

        components: {
            vueDropzone: vue2Dropzone
        },
        
        data() {
            return {
                title: '',
                description: '',
                minZoom: 15,
                maxZoom: 24,
                dropzoneOptions: {
                    url: '/api/projects',
                    acceptedFiles: "image/*",
                    maxFilesize: 2000,
                    createImageThumbnails: false,
                    dictDefaultMessage: "Drag a .tif file here"
                },
            }
        },

        mounted() {

        },

        methods: {
            
            postProject() {
                axios.get('/api/projects')
                .then(response => {
                    this.projects = response.data
                })
            },

            sendingEvent (file, xhr, formData) {
                formData.append('title', this.title);
                formData.append('description', this.description);
                formData.append('minZoom', this.minZoom);
                formData.append('maxZoom', this.maxZoom);
            },

            projectCreated() {
                setTimeout(() => {
                    location.href = '/projects'
                }, 2000)
            }

        }
        
    }
</script>
