<template>
    <el-form class="form_editor" :data="data" label-position="top">
        <el-form-item label="First name">
            <el-input placeholder="First Name" v-model="data.first_name"></el-input>
        </el-form-item>
        <el-form-item label="Last name">
            <el-input placeholder="Last Name" v-model="data.last_name"></el-input>
        </el-form-item>
        <el-form-item label="Email">
            <el-input placeholder="Email" type="email" v-model="data.email"></el-input>
        </el-form-item>
        <el-form-item label="Address Line 1">
            <el-input placeholder="Address Line 1" v-model="data.address_line_1"></el-input>
        </el-form-item>
        <el-form-item label="Address Line 2">
            <el-input placeholder="Address Line 2" v-model="data.address_line_2"></el-input>
        </el-form-item>
        <el-form-item label="City">
            <el-input placeholder="City" v-model="data.city"></el-input>
        </el-form-item>
        <el-form-item label="State">
            <el-input placeholder="State" v-model="data.state"></el-input>
        </el-form-item>
        <el-form-item label="Zip Code">
            <el-input placeholder="Zip Code" v-model="data.zip"></el-input>
        </el-form-item>
        <el-form-item label="Status">
            <el-select v-model="data.status">
                <el-option
                    v-for="(status,statusKey) in statuses"
                    :key="statusKey"
                    :label="status"
                    :value="statusKey"></el-option>
            </el-select>
        </el-form-item>
        <el-form-item label="Country">
            <el-input placeholder="Country" v-model="data.country"></el-input>
        </el-form-item>
    </el-form>
</template>

<script type="text/babel">
    export default {
        name: 'Form',
        props: ['data'],
        data() {
            return {
                statuses: {
                    active: 'Active',
                    inactive: 'In-Active',
                    pending: 'Pending'
                }
            }
        }
    }
</script>
