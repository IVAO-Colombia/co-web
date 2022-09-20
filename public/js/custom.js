new Vue({
    el: "#vue",
    data: {
        items: [],
        totalItems: 0,
        perPage: 0,
        page: 0,
        pages: 0,
    },

    methods: {
        getFra() {
            let vm = this;
            axios
                .get(
                    "https://api.ivao.aero/v2/fras?page=1&perPage=40&isActive=true&members=false&divisionId=CO&expand=true",
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
        },
    },

    mounted() {
        this.getFra();
    },
});
