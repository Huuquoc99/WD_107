import React from "react";
import {
  Layout,
  Menu,
  Breadcrumb,
  Typography,
  Avatar,
  Dropdown,
  Space,
} from "antd";
import { Outlet, Link } from "react-router-dom";
import { UserOutlined, LaptopOutlined, DownOutlined } from "@ant-design/icons";

const { Header, Content, Sider } = Layout;
const { Title } = Typography;

const menu = (
  <Menu style={{ fontSize: "16px", width: "200px", marginTop: "5px" }}>
    <Menu.Item key="1">
      <Link to="/profile">Profile</Link>
    </Menu.Item>
    <Menu.Item key="2">
      <Link to="/settings">Settings</Link>
    </Menu.Item>
    <Menu.Divider />
    <Menu.Item key="3">
      <Link to="/logout">Logout</Link>
    </Menu.Item>
  </Menu>
);

const Dashboard = () => (
  <Layout style={{ minHeight: "100vh" }}>
    <Header
      className="header flex items-center ml-[-20px]"
      style={{
        backgroundColor: "#001529",
        display: "flex",
        justifyContent: "space-between",
        alignItems: "center",
      }}
    >
      <div className="logo">
       <Link to="/admin">
       <Title level={3} style={{ color: "white", margin: 0 }}>
          Dashboard
        </Title></Link>
      </div>
      <Space>
        <Dropdown overlay={menu} trigger={["click"]}>
          <a onClick={(e) => e.preventDefault()}>
            <Avatar icon={<UserOutlined />} />
            <DownOutlined style={{ color: "white", marginLeft: 8 }} />
          </a>
        </Dropdown>
      </Space>
    </Header>
    <Layout>
      <Sider width={200} className="site-layout-background">
        <Menu
          mode="inline"
          defaultSelectedKeys={["1"]}
          style={{ height: "100%", borderRight: 0 }}
        >
          <Menu.Item key="1" icon={<UserOutlined />}>
            <Link to="auth">User</Link>
          </Menu.Item>
          <Menu.Item key="2" icon={<LaptopOutlined />}>
            <Link to="categories">Categories</Link>
          </Menu.Item>
        </Menu>
      </Sider>
      <Layout style={{ padding: "0 24px 24px" }}>
        <Breadcrumb style={{ margin: "16px 0" }}>
          <Breadcrumb.Item>Dashboard</Breadcrumb.Item>
          <Breadcrumb.Item>Admin</Breadcrumb.Item>
        </Breadcrumb>
        <Content
          className="site-layout-background"
          style={{
            padding: 24,
            margin: 0,
            minHeight: 280,
          }}
        >
          <Outlet />
        </Content>
      </Layout>
    </Layout>
  </Layout>
);

export default Dashboard;
