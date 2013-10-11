@AppController = Backbone.Router.extend
  members_view : null
  routes       :
    ''       : 'list'
    '!:slug' : 'member'
  list         : ->
    @members_view = new MembersView() unless @members_view
    @members_view.render()
  member       : ( slug ) ->
    @members_view = new MembersView() unless @members_view
    @members_view.render( slug )

$ =>
  # ------- BEGIN --------

  new AppController()
  Backbone.history.start()