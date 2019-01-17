<template>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th># Detections</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="project in projects" :key="project.íd">
                    <td v-text="project.title"></td>
                    <td v-text="project.description"></td>
                    <td v-text="project.detections_count"></td>
                    <td v-text="getProjectStatus(project.status)"></td>
                    <td>
                        <button class="btn btn-light float-right" @click="deleteProject(project)">Delete</button>
                        <a :href="'/projects/' + project.id + '/edit'" class="btn btn-light float-right mr-2">Edit</a>
                        <a :href="'/projects/' + project.id" class="btn btn-light float-right mr-2">View map</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {

        name: 'project-index',
        
        data() {
            return {
                projects: []
            }
        },

        mounted() {
            this.fetchProjects()
            
            setInterval(() => {
                this.fetchProjects()
            }, 10000);
        },

        methods: {
            
            fetchProjects() {
                axios.get('/api/projects')
                .then(response => {
                    this.projects = response.data
                })
            },

            getProjectStatus(status) {
                switch (status) {
                    case 'queue':
                        return 'Waiting ..'
                        break;
                    case 'processing':
                        return 'Processing ..'
                        break;
                    case 'finished':
                        return 'Done'
                        break;
                
                    default:
                        return ''
                        break;
                }
            },

            deleteProject(project) {
                if(confirm('This action is permanent')) {
                    axios.delete('/api/projects/' + project.id)
                    .then(response => {
                        this.fetchProjects()
                    })
                }
            }

        }
        
    }
</script>
