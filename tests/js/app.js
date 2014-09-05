/**
 * @jsx React.DOM
 */
var Person = React.createClass({displayName: 'Person',
    getInitialState: function () {
        return {
            nome: 'Sem nome :(',
            email: 'Sem email :('
        };
    },
    render: function () {
        return (
            React.DOM.div({class: "person"}, 
                React.DOM.p(null, React.DOM.strong(null, "Nome: "), React.DOM.span(null,  this.props.nome)), 
                React.DOM.p(null, React.DOM.strong(null, "E-mail: "), React.DOM.span(null,  this.props.email))
            )
        );
    }
});
