<template>
    <div>

        <div class="form-group">
            <h3 for="data">Add labels to project</h3>
            <label for="labelTitle">Label title</label>
            <input type="text" id="labelTitle" class="form-control" v-model="labelTitle">
        </div>

        <table class="table" v-if="labels.length">
            <thead>
                <tr>
                    <th>Title</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="label in labels" :key="label.id">
                    <td v-text="label.title"></td>
                    <td>
                        <button class="btn btn-light float-right" @click="deleteLabel(label)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <h4 v-else>There are no labels linked to this project yet</h4>

        <hr>

    </div>
</template>

<script>
export default {
    
    name: 'project-labels',

    props: ['projectId'],

    data() {
        return {
            labelTitle: '',
            labels: [],
        }
    },

    mounted() {
        this.fetchLabels()
    },

    methods: {

        fetchLabels() {
            axios.get('/api/projects/' + this.projectId + '/labels')
            .then(response => {
                this.labels = response.data
            })
        },

        deleteLabel(label) {
            if(confirm('This action is permanent')) {
                axios.delete('/api/projects/' + this.projectId + '/labels/' + label.id)
                .then(response => {
                    this.fetchLabels()
                })
            }
        }

    }

}
</script>
