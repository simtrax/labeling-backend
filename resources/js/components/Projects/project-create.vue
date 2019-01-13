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

        <!-- <form @submit="postProject()" enctype="multipart/form-data"> -->


        <!-- </form> -->
        <!-- {!! Form::open(array('route' => 'projects.store', 'class' => 'form-horizontal', 'files' => true)) !!}
            
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">Description</label>
                {!!Form::textarea('description', old('description'), array('class' => 'form-control'))!!}
                
                @include('helpers.form-errors', ['type' => 'description'])
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('minZoom') ? 'has-error' : '' }}">
                        <label for="minZoom">Minimum tile zoom</label>
                        {!!Form::text('minZoom', old('minZoom'), array('class' => 'form-control'))!!}
                        
                        @include('helpers.form-errors', ['type' => 'minZoom'])
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('maxZoom') ? 'has-error' : '' }}">
                        <label for="maxZoom">Maximum tile zoom</label>
                        {!!Form::text('maxZoom', old('maxZoom'), array('class' => 'form-control'))!!}
                        
                        @include('helpers.form-errors', ['type' => 'maxZoom'])
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="file">GeoTif file</label>
                {!! Form::file('file', ['multiple' => true, 'class' => 'form-control-file']) !!}
                
                @include('helpers.form-errors', ['type' => 'file'])
            </div>

            <button type="submit" class="btn btn-primary publishtourbutt2">
                Save Photos
            </button>
        {!! Form::close() !!} -->
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
                    headers: { 
                        // "Authorization": "Bearer " + this.$auth.token()
                    },
                    acceptedFiles: "image/*",
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
