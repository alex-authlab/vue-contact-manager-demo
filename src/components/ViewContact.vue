<template>
    <div class="vcm_all_contacts">
        <div class="section_header">
            <h3 class="section_title">View Contact</h3>
            <div class="section_actions">
                <el-button @click="gotoAll()" size="small" type="primary">View All</el-button>
            </div>
        </div>

        <div v-loading="loading" class="section_body">
            <contact-editor :data="contact" />
            <el-button v-loading="saving" type="success" @click="saveContact()">Update Data</el-button>
        </div>
    </div>
</template>

<script type="text/babel">
    import ContactEditor from './_ContactEditor';
    export default {
        name: 'ViewContact',
        props: ['id'],
        components: {
            ContactEditor
        },
        data() {
            return {
                loading: false,
                contact: {},
                saving: false
            }
        },
        methods: {
            fetchContact() {
                this.loading = true;
                this.$request('get', 'contacts/' + this.id)
                    .then(response => {
                        this.contact = response;
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            saveContact() {
                this.saving = true;
                this.$request('PUT', 'contacts/' + this.id, {
                    contact: this.contact
                })
                    .then(response => {
                        this.$notify.success(response.message);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.saving = false;
                    });
            },
            gotoAll() {
                this.$router.push({ name: 'home' });
            }
        },
        mounted() {
            this.fetchContact();
        }
    }
</script>
