import React, { Component } from 'react';

class Projects extends Component {


    render() {
        console.log(this.props);
        return (
            <div className="Projects">
                <h1>Hello {this.props.projects.title}</h1>
            </div>
        );
    }
}

export default Projects;
