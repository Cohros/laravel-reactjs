/**
 * @jsx React.DOM
 */
var Person = React.createClass({displayName: "Person",
    getInitialState: function () {
        return {
            nome: 'Sem nome :(',
            email: 'Sem email :('
        };
    },
    render: function () {
        return (
            React.createElement("div", {class: "person"}, 
                React.createElement("p", null, React.createElement("strong", null, "Nome: "), React.createElement("span", null,  this.props.nome)), 
                React.createElement("p", null, React.createElement("strong", null, "E-mail: "), React.createElement("span", null,  this.props.email))
            )
        );
    }
});
