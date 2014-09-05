/**
 * @jsx React.DOM
 */

var App = require('../app');
require('./contact');

var Contact = App.components.Contact;
var React = App.libs.React;

var ContactList = React.createClass({
    getInitialState: function () {
        if (this.props.data) {
            return this.props.data;
        }
        return {
            contacts: []
        };
    },
    render: function () {
        return (
            <div className="contact-list">
                <h1>Contacts List</h1>
                <div className="row">
                    { this.state.contacts.map(function (contact) {
                        return <Contact data={ contact } />
                    }) }
                </div>
            </div>
        );
    }
});

App.components.ContactList = ContactList;