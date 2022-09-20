new Vue({
    el: "#vue",
    data: {
        items: [],
        totalItems: 0,
        perPage: 0,
        page: 0,
        pages: 0,
        search: "",
    },

    methods: {
        async getFra() {
            let vm = this;
            await axios
                .get(
                    "https://api.ivao.aero/v2/fras?page=1&perPage=100&isActive=true&members=false&divisionId=CO&expand=true",
                    {
                        headers: {
                            apiKey: "krkU4NF0gcl8rXGC4ZdpeUAyneZrLON5",
                        },
                    }
                )
                .then(function (response) {
                    // handle success
                    // this.items = response.data.items;
                    Vue.set(vm, "items", response.data.items);
                    // vm.items.push(response.data.items);
                    vm.totalItems = response.data.totalItems;
                    vm.perPage = response.data.perPage;
                    vm.page = response.data.page;
                    vm.pages = response.data.pages;
                    console.log(vm.items);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });

            await axios
                .get(
                    "https://api.ivao.aero/v2/fras?page=2&perPage=100&isActive=true&members=false&divisionId=CO&expand=true",
                    {
                        headers: {
                            apiKey: "krkU4NF0gcl8rXGC4ZdpeUAyneZrLON5",
                        },
                    }
                )
                .then(function (response) {
                    // handle success
                    // this.items = response.data.items;
                    Vue.set(vm, "items", [...vm.items, ...response.data.items]);
                    // vm.items.push(response.data.items);
                    console.log(vm.items);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
        },
    },
    computed: {
        filteredItems() {
            let vm = this;
            return vm.items.filter((item) => {
                if (item.subcenter) {
                    return (
                        item.subcenter.composePosition
                            .toLowerCase()
                            .indexOf(vm.search.toLowerCase()) > -1
                    );
                } else {
                    return (
                        item.atcPosition.composePosition
                            .toLowerCase()
                            .indexOf(vm.search.toLowerCase()) > -1
                    );
                }
            });
        },
    },

    mounted() {
        this.getFra();
    },
});
