<template>
    <div class="members-style">
        <div style="margin-bottom: 20px;">
            <h2>FullStack Test</h2>
        </div>
        <div class="table-style">
            <input
                class="input"
                type="text"
                v-model="search"
                placeholder="Search..."
                @input="resetPagination()"
                style="width: 250px;"
            />
            <select v-model="length" @change="resetPagination()">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
            </select>
        </div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th
                        v-for="column in columns"
                        :key="column.name"
                        @click="sortBy(column.name)"
                        :class="
                            sortKey === column.name
                                ? sortOrders[column.name] > 0
                                    ? 'sorting_asc'
                                    : 'sorting_desc'
                                : 'sorting'
                        "
                        style="width: 40%; cursor:pointer;"
                    >
                        {{ column.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="members in paginatedmembers" :key="members.id">
                    <td>{{ members.id }}</td>
                    <td>{{ members.firstname }}</td>
                    <td>{{ members.surname }}</td>
                    <td>{{ members.email }}</td>
                    <td>{{ members.gender }}</td>
                    <td>{{ members.joined_date }}</td>
                </tr>
            </tbody>
        </table>
        <div>
            <nav class="pagination" v-if="!tableShow.showdata">
                <span class="page-stats"
                    >{{ pagination.from }} - {{ pagination.to }} of
                    {{ pagination.total }}</span
                ><a
                    v-if="pagination.prevPageUrl"
                    class="btn btn-sm btn-primary pagination-previous"
                    @click="--pagination.currentPage"
                >
                    Prev </a
                ><a
                    class="btn btn-sm btn-primary pagination-previous"
                    v-else
                    disabled
                >
                    Prev </a
                ><a
                    v-if="pagination.nextPageUrl"
                    class="btn btn-sm pagination-next"
                    @click="++pagination.currentPage"
                >
                    Next </a
                ><a
                    class="btn btn-sm btn-primary pagination-next"
                    v-else
                    disabled
                >
                    Next
                </a>
            </nav>
            <nav class="pagination" v-else>
                <span class="page-stats"
                    >{{ pagination.from }} - {{ pagination.to }} of
                    {{ filteredmembers.length
                    }}<span
                        v-if="`filteredmembers.length < pagination.total`"
                    ></span></span
                ><a
                    v-if="pagination.prevPage"
                    class="btn btn-sm btn-primary pagination-previous"
                    @click="--pagination.currentPage"
                >
                    Prev </a
                ><a
                    class="btn btn-sm pagination-previous btn-primary"
                    v-else
                    disabled
                >
                    Prev </a
                ><a
                    v-if="pagination.nextPage"
                    class="btn btn-sm btn-primary pagination-next"
                    @click="++pagination.currentPage"
                >
                    Next </a
                ><a
                    class="btn btn-sm pagination-next btn-primary"
                    v-else
                    disabled
                >
                    Next
                </a>
            </nav>
        </div>
    </div>
</template>
<script>
export default {
    created() {
        this.getmembers();
        Fire.$on("reloadmembers", () => {
            this.getmembers();
        });
    },
    data() {
        let sortOrders = {};
        let columns = [
            { label: "ID", name: "id" },
            { label: "Firstname", name: "firstname" },
            { label: "Surname", name: "surname" },
            { label: "Email", name: "email" },
            { label: "Gender", name: "gender" },
            { label: "Joined Date", name: "joined_date" }
        ];
        columns.forEach(column => {
            sortOrders[column.name] = -1;
        });
        return {
            members: [],
            columns: columns,
            sortKey: "joined_date",
            sortOrders: sortOrders,
            length: 10,
            search: "",
            tableShow: { showdata: true },
            pagination: {
                currentPage: 1,
                total: "",
                nextPage: "",
                prevPage: "",
                from: "",
                to: ""
            }
        };
    },
    methods: {
        getmembers() {
            axios
                .get("/members/", { params: this.tableShow })
                .then(response => {
                    console.log("The data: ", response.data);
                    this.members = response.data;
                    this.pagination.total = this.members.length;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        paginate(array, length, pageNumber) {
            this.pagination.from = array.length
                ? (pageNumber - 1) * length + 1
                : " ";
            this.pagination.to =
                pageNumber * length > array.length
                    ? array.length
                    : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : "";
            this.pagination.nextPage =
                array.length > this.pagination.to ? pageNumber + 1 : "";
            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage = "";
            this.pagination.nextPage = "";
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value);
        }
    },
    computed: {
        filteredmembers() {
            let members = this.members;
            if (this.search) {
                members = members.filter(row => {
                    return Object.keys(row).some(key => {
                        return (
                            String(row[key])
                                .toLowerCase()
                                .indexOf(this.search.toLowerCase()) > -1
                        );
                    });
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                members = members.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, "name", sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (
                        this.columns[index].type &&
                        this.columns[index].type === "date"
                    ) {
                        return (
                            (a === b
                                ? 0
                                : new Date(a).getTime() > new Date(b).getTime()
                                ? 1
                                : -1) * order
                        );
                    } else if (
                        this.columns[index].type &&
                        this.columns[index].type === "number"
                    ) {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            return members;
        },
        paginatedmembers() {
            return this.paginate(
                this.filteredmembers,
                this.length,
                this.pagination.currentPage
            );
        }
    }
};
</script>
