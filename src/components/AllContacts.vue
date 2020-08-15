<template>
    <div class="vcm_all_contacts">
        <div class="section_header">
            <h3 class="section_title">All Contacts</h3>
            <div class="section_actions">
                <el-button @click="addNewModal = true" size="small" type="primary">Add New Contact</el-button>
            </div>
        </div>

        <div v-loading="loading" class="section_body">
            <el-table :data="contacts">
                <el-table-column prop="first_name" label="First name"></el-table-column>
                <el-table-column prop="last_name" label="Last name"></el-table-column>
                <el-table-column prop="email" label="Email"></el-table-column>
                <el-table-column prop="country" label="Country"></el-table-column>
                <el-table-column label="Action">
                    <template slot-scope="scope">
                        <el-button type="primary" @click="gotoView(scope.row.id)" icon="el-icon-view"
                                   size="mini"></el-button>
                        <el-button type="danger" @click="handleDelete(scope.row.id)" icon="el-icon-delete"
                                   size="mini"></el-button>
                    </template>
                </el-table-column>
            </el-table>

            <el-pagination
                layout="prev, pager, next"
                :page-size.sync="paginate.per_page"
                :current-page.sync="paginate.page"
                :total="paginate.total"
                @current-change="fetchContacts"
            />
        </div>

        <el-dialog
            title="Create New Contact"
            :visible.sync="addNewModal"
            width="60%">
            <contact-editor :data="new_contact" />
            <span slot="footer" class="dialog-footer">
                <el-button @click="addNewModal = false">Cancel</el-button>
                <el-button type="primary" @click="createContact()">Create New</el-button>
            </span>
        </el-dialog>

    </div>
</template>

<script type="text/babel">
    import ContactEditor from './_ContactEditor';

    export default {
        name: 'AllContacts',
        components: {
            ContactEditor
        },
        data() {
            return {
                contacts: [],
                loading: false,
                paginate: {
                    total: 0,
                    per_page: 10,
                    page: 1
                },
                addNewModal: false,
                creating: false,
                new_contact: {}
            }
        },
        methods: {
            fetchContacts() {
                this.loading = true;
                this.$request('get', 'contacts', {
                    page: 1,
                    per_page: 20
                })
                    .then(response => {
                        this.contacts = response.data;
                        this.paginate.total = parseInt(response.total);
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },
            createContact() {
                this.creating = true;
                this.$request('POST', 'contacts', {
                    contact: this.new_contact
                })
                    .then(response => {
                        this.$notify.success(response.message);
                        this.addNewModal = false;
                        this.new_contact = {};
                        this.fetchContacts();
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.creating = false;
                    });
            },
            gotoView(id) {
                this.$router.push({
                    name: 'viewContact',
                    params: {
                        id: id
                    }
                });
            },
            handleDelete(id) {
                this.loading = true;
                this.$request('DELETE', 'contacts/' + id)
                    .then(response => {
                        this.$notify.success(response.message);
                        this.fetchContacts();
                    })
                    .catch((error) => {
                        console.log(error);
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            }
        },
        mounted() {
            this.fetchContacts();
        }
    }
</script>
