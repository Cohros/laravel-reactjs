/**
 * @jsx React.DOM
 */
var App = require('../app');
var React = App.libs.React;

var Contact = React.createClass({
    handleClick: function () {
        confirm('você quer mesmo remover?');
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
                            <span className="btn btn-danger" onClick={ this.handleClick }>Remover</span>
                            <span className="btn btn-default">Editar</span>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

App.components.Contact = Contact;