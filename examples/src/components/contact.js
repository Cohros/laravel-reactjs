/**
 * @jsx React.DOM
 */
var App = require('../app');
var React = App.libs.React;

var Contact = React.createClass({
    handleDelete: function () {
        confirm('delete button');
    },
    handleEdit: function () {
        alert('edit button');
    },
    render: function () {
        return (
            <div className="col-md-4">
                <div className="panel panel-default">
                    <div className=" panel-body">
                        <h3>{ this.props.data.nome }</h3>
                        <p>
                            <a href="#">{ this.props.data.email }</a>
                            <br />
                            { this.props.data.title }</p>
                        <div className="btn-group">
                            <span className="btn btn-danger" onClick={ this.handleDelete }>Remover</span>
                            <span className="btn btn-default" onClick={ this.handleEdit }>Editar</span>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

App.components.Contact = Contact;