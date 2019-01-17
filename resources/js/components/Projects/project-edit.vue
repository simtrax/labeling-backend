<template>
    <div>

        <vue-dropzone id="dropzone" v-on:vdropzone-sending="sendingEvent" v-on:vdropzone-success="projectCreated" :options="dropzoneOptions">
        </vue-dropzone><br>
        
        <div class="form-group">
            <h3 for="data">obj.data file</h3>
            <textarea id="data" class="form-control" v-model="data"></textarea>
        </div>

        <div class="form-group">
            <h3 for="names">obj.names file</h3>
            <textarea id="names" class="form-control" v-model="names"></textarea>
        </div>

        <div class="form-group">
            <h3 for="description">Config file</h3>
            <div class="col-md-4">
                <label>Number of classes</label>
                <input type="number" class="form-control" v-model="numClasses">
            </div>
            <p class="ml-3 mt-2">
                Change line classes=<b>{{numClasses}}</b> to your number of objects in each of 3 [yolo]-layers:<br>
                <a href="" @click.prevent="goToCfgLine(610)">610</a><br>
                <a href="" @click.prevent="goToCfgLine(696)">696</a><br>
                <a href="" @click.prevent="goToCfgLine(783)">783</a><br>
                Change [filters=255] to filters=<b>{{numFilters}}</b> in the 3 [convolutional] before each [yolo] layer<br>
                <a href="" @click.prevent="goToCfgLine(603)">603</a><br>
                <a href="" @click.prevent="goToCfgLine(689)">689</a><br>
                <a href="" @click.prevent="goToCfgLine(776)">776</a><br>
            </p>

            <textarea id="cfg" class="form-control" v-model="cfg"></textarea>
        </div>

        <button class="btn btn-primary mt-8" @click="updateProject">Save files</button>

    </div>
</template>

<script>

    import CodeMirror from 'codemirror';
    import 'codemirror/lib/codemirror.css';
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {

        name: 'project-detection',

        components: {
            vueDropzone: vue2Dropzone
        },

        props: ['project'],
        
        data() {
            return {
                numClasses: 1,
                names: this.project.darknetNamesFile,
                data: this.project.darknetDataFile,
                cfg: this.project.darknetCfgFile,
                cfgEditor: null,
                namesEditor: null,
                dataEditor: null,
                dropzoneOptions: {
                    url: '/api/projects',
                    // acceptedFiles: "image/*",
                    createImageThumbnails: false,
                    dictDefaultMessage: "Drag a trained model file here"
                },
            }
        },

        mounted() {

            this.cfgEditor = CodeMirror.fromTextArea(document.getElementById("cfg"), {
                lineNumbers: true,
            });
            this.namesEditor = CodeMirror.fromTextArea(document.getElementById("names"), {
                lineNumbers: true,
            });
            this.dataEditor = CodeMirror.fromTextArea(document.getElementById("data"), {
                lineNumbers: true,
            });

        },

        computed: {
            numFilters() {
                return (this.numClasses + 5) * 3
            }
        },

        methods: {

            sendingEvent (file, xhr, formData) {
                formData.append('project_id', this.project.id);
            },

            projectCreated() {
                setTimeout(() => {
                    // location.href = '/projects'
                }, 2000)
            },
            
            goToCfgLine(lineNumber) {
                var t = this.cfgEditor.charCoords({line: lineNumber, ch: 0}, "local").top; 
                var middleHeight = this.cfgEditor.getScrollerElement().offsetHeight / 2; 
                this.cfgEditor.scrollTo(null, t - middleHeight - 5); 

                // this.cfgEditor.markText({line:lineNumber, ch: 0}, {line: lineNumber + 1, ch: 9}, {className: "errorHighlight"});
            },

            updateProject() {
                axios.patch('/api/projects/' + this.project.id, {
                    title: this.project.title,
                    darknetCfg: this.cfgEditor.getValue(),
                    darknetNames: this.namesEditor.getValue(),
                    darknetData: this.dataEditor.getValue(),
                }).then(response => {
                    console.log(response.data);
                })
            }

        }
        
    }
</script>
