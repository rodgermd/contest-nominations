@Member = Backbone.Model.extend
  idAttribute: 'slug'
  defaults   :
    company       : null
    position      : null
    experience    : null
    description   : null
    name          : null
    age           : null
    city          : null
    country       : null
    profile_image : null
    list_image    : null
    images        : null
    votes         : 0
    place         : null
    allow_vote    : null
    slug          : null
  initialize : ->
    member_data = @get 'member'
    @set 'name', member_data.name
    @set 'age', member_data.age
    @set 'city', member_data.city
    @set 'country', member_data.country
    delete @attributes.member

@Members = Backbone.Collection.extend model : @Member