<template>
    <div>

        <div class="form-group">
            <h3 for="data">Add models to project</h3>
            <label for="modelTitle">Model title</label>
            <input type="text" id="modelTitle" class="form-control" v-model="modelTitle">
        </div>

        <vue-dropzone id="dropzone" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-success="modelUploaded" :options="dropzoneOptions">
        </vue-dropzone><br>

        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="model in models" :key="model.id">
                    <td v-text="model.title"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <hr>

    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'

export default {
    
    name: 'project-models',

    components: {
        vueDropzone: vue2Dropzone, 
    },

    props: ['projectId'],

    data() {
        return {
            models: [],
            modelTitle: '',
            dropzoneOptions: {
                url: '/api/projects/' + this.projectId + '/yolomodels',
                // acceptedFiles: "image/*",
                createImageThumbnails: false,
                dictDefaultMessage: "Drag a trained model file here"
            },
        }
    },

    mounted() {
        this.fetchModels()
    },

    methods: {

        sendingEvent (file, xhr, formData) {
            formData.append('title', this.modelTitle);
            formData.append('project_id', this.projectId);
        },

        modelUploaded() {
            this.title = ''

            this.fetchModels()
        },

        fetchModels() {
            axios.get('/api/projects/' + this.projectId + '/yolomodels')
            .then(response => {
                this.models = response.data
            })
        }

    }

}
</script>
