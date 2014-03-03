define([
    'backbone',
    'underscore',
    'backbone-paginator',
    'user_model'
], function(
        Backbone,
        _,
        Paginator,
        UserModel
        ) {
    var UtilisateurPaginatedCollection = Paginator.clientPager.extend({
        // As usual, let's specify the model to be used
        // with this collection
        model: UserModel,
        // current page to query from the service
        page: 1,
        // how many results to display per 'client page'
        displayPerPage: 5,
        // what field should the results be sorted on?
        sortField: undefined,
        // what direction should the results be sorted in?
        sortDirection: 'asc',
        filterThematicValue: undefined,
        initialize: function(models, options) {
            options = _.defaults(options || {}, {
                displayPerPage: 5
            });
            this.displayPerPage = options.displayPerPage;
        },
        nextPage: function() {
            var self = this;
            self.page = ++self.page;
            self.pager();
        },
        previousPage: function() {
            var self = this;
            self.page = --self.page || 1;
            self.pager();
        },
        goTo: function(page) {
            var self = this;
            self.page = parseInt(page, 10);
            self.pager();
        },
        howManyPer: function(displayPerPage) {
            var self = this;
            self.page = 1;
            self.displayPerPage = displayPerPage;
            self.pager();
        },
        setSort: function(column, direction) {
            var self = this;
            self.pager(column, direction);
        },
        pager: function(sort, direction) {
            if (!_.isNumber(this.page)) {
                this.page = 1;
            }

            if (sort) {
                this.sortField = sort;
            }

            if (direction) {
                this.sortDirection = direction;
            }

            if (this.origModels === undefined) {
                this.origModels = _.clone(this.models);
            }

            var start,
                    stop,
                    models = _.clone(this.origModels);

            if (typeof this.sortField != "undefined" && 'default' != this.sortField) {
                models = this._sort(models, this.sortField, this.sortDirection);
            }

            this.totalRecords = models.length;
            this.totalPages = Math.ceil(this.totalRecords / this.displayPerPage);

            if (this.page > 1) {
                this.reset();
                start = (this.page - 1) * this.displayPerPage;
                stop = start + this.displayPerPage;
                for (var i = start; i <= stop; i++) {
                    if (!this.models[i] && models[i]) {
                        this.add(models[i].clone());
                    }
                }
            } else {
                start = 0;
                stop = start + this.page * this.displayPerPage;
                this.models = models.slice(start, stop);
                this.reset(this.models);
            }
        },
        info: function() {
            var self = this,
                    info = {};

            info = {
                totalRecords: self.totalRecords,
                page: self.page,
                perPage: self.displayPerPage,
                totalPages: self.totalPages,
                lastPage: self.totalPages,
                lastPagem1: self.totalPages - 1,
                previous: false,
                next: false,
                page_set: [],
                startRecord: (self.page - 1) * self.displayPerPage + 1,
                endRecord: Math.min(self.totalRecords, self.page * self.displayPerPage)
            };

            if (self.page > 1) {
                info.prev = self.page - 1;
            }

            if (self.page < info.totalPages) {
                info.next = self.page + 1;
            }

            info.pageSet = self.setPagination(info);

            self.information = info;
            return info;
        },
        more: function() {
            // console.log('more');
            this.pager(this.page + 1);
        },
        getSortField: function() {
            return this.sortField;
        }

    });

    return UtilisateurPaginatedCollection;
});


