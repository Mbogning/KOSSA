import React from 'react';
import Top5 from '../../components/Top5';
import MenuLeft from '../../components/news/MenuLeft';

class Base extends React.Component {
   

    componentDidMount() {
          const script = document.createElement("script");
          script.src = "/kossa/js/custom.js";
          script.async = true;
          document.body.appendChild(script);
    }

   

    render() {
        return (
            <div>
                {this.props.banner}
                <div className="container">
                    <div className="row ">
                        <div className="col-md-2 mt-5 mb-5 p-md-1">
                            <MenuLeft />
                            <Top5 />
                        </div>
                        <div className="col-md-7 mt-md-5 p-md-0">
                            {this.props.content}
                        </div>
                        <div className="col-md-3 mt-1">
                        {this.props.contentRight}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Base;



















