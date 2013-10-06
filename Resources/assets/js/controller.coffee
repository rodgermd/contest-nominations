@AppController = Backbone.Router.extend
  members_view : null
  routes       :
    ''     : 'list'
    '!:id' : 'member'
  list         : ->
    @members_view = new MembersView() unless @members_view
    @members_view.render()
  member       : ( id ) ->
    @members_view = new MembersView() unless @members_view
    @members_view.render( id )

$ =>
  # ------- BEGIN --------

  new AppController()
  Backbone.history.start()